<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Metadata;

use BackedEnum;
use Exception;
use Matraux\JsonOrm\Codec\BackedEnumCodec;
use Matraux\JsonOrm\Codec\Codec;
use Matraux\JsonOrm\Codec\CollectionCodec;
use Matraux\JsonOrm\Codec\EntityCodec;
use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\JsonOrm\Json\Property;
use ReflectionAttribute;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

/**
 * @property-read string $name
 * @property-read string $index
 * @property-read class-string $class
 * @property-read ?Codec $codec
 * @property-read ReflectionProperty $reflection
 */
final class Metadata
{
	protected string $name;

	protected string $index;

	/** @var class-string */
	protected string $class;

	protected ?Codec $codec;

	protected ReflectionProperty $reflection;

	/**
	 * @throws CodecException
	 * @throws RuntimeException
	 */
	public function __construct(ReflectionProperty $reflection)
	{
		$this->reflection = $reflection;
		$this->name = $reflection->name;
		$this->class = $reflection->class;
		$this->index = $this->resolveIndex();
		$this->codec = $this->resolveCodec();
	}

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		switch ($name) {
			case 'name':
				return $this->name;
			case 'index':
				return $this->index;
			case 'class':
				return $this->class;
			case 'codec':
				return $this->codec;
			case 'reflection':
				return $this->reflection;
			default:
				throw new RuntimeException(sprintf('Undefined property %s::$%s.', static::class, $name));
		}
	}

	public function isInitialized(Entity $entity): bool
	{
		return $this->reflection->isInitialized($entity);
	}

	protected function resolveIndex(): string
	{
		$doc = $this->reflection->getDocComment();

		return $doc && preg_match('/@index\s+(\S+)/', $doc, $matches) ? $matches[1] : $this->reflection->name;
	}

	protected function resolveCodec(): ?Codec
	{
		$doc = $this->reflection->getDocComment();
		if($doc && preg_match('/@codec\s+([^\r\n]+)/', $doc, $matches)) {
			/** @var class-string<Codec> $codec */
			$codec = $matches[1];
			return new $codec;
		}

		$type = $this->reflection->getType();
		if (!$type instanceof ReflectionNamedType) {
			return null;
		}

		switch (strtolower($type->getName())) {
			case 'parent':
				if(!$parent = $this->reflection->getDeclaringClass()->getParentClass()) {
					throw new RuntimeException(sprintf('Unresolvable type parent for %s::$%s.', $this->reflection->class, $this->reflection->name));
				}
				$type = $parent->name;
				break;
			case 'self':
				$type = $this->reflection->class;
				break;
			default:
				$type = $type->getName();
				break;
		}

		switch (true) {
			case is_subclass_of($type, Entity::class):
				return new EntityCodec($type);
			case is_subclass_of($type, Collection::class):
				return new CollectionCodec($type);
			default:
				return null;
		}
	}
}
