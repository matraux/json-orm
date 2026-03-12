<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Metadata;

use BackedEnum;
use Matraux\JsonOrm\Codec\BackedEnumCodec;
use Matraux\JsonOrm\Codec\Codec;
use Matraux\JsonOrm\Codec\CollectionCodec;
use Matraux\JsonOrm\Codec\EntityCodec;
use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\JsonOrm\Json\Property;
use PropertyHookType;
use ReflectionAttribute;
use ReflectionNamedType;
use ReflectionProperty;
use RuntimeException;

final readonly class Metadata
{
	public string $name;

	public string $index;

	/** @var class-string */
	public string $class;

	public ?Codec $codec;

	/**
	 * @throws CodecException
	 * @throws RuntimeException
	 */
	public function __construct(public ReflectionProperty $reflection)
	{
		$this->name = $reflection->name;
		$this->class = $reflection->class;
		$this->index = $this->resolveIndex();
		$this->codec = $this->resolveCodec();
	}

	public function isInitialized(Entity $entity): bool
	{
		return $this->reflection->isInitialized($entity) || $this->reflection->getHook(PropertyHookType::Get);
	}

	protected function resolveIndex(): string
	{
		$attributes = $this->reflection->getAttributes(Property::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new RuntimeException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->reflection->class, $this->reflection->name, Property::class));
		}

		return array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;
	}

	protected function resolveCodec(): ?Codec
	{
		$attributes = $this->reflection->getAttributes(Codec::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new CodecException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->reflection->class, $this->reflection->name, Codec::class));
		}

		if ($codec = array_shift($attributes)?->newInstance()) {
			return $codec;
		}

		$type = $this->reflection->getType();
		if (!$type instanceof ReflectionNamedType) {
			return null;
		}

		$type = match (strtolower($type->getName())) {
			'parent' => ($parent = $this->reflection->getDeclaringClass()->getParentClass()) ? $parent->name : throw new RuntimeException(sprintf('Unresolvable type parent for %s::$%s.', $this->reflection->class, $this->reflection->name)),
			'self' => $this->reflection->class,
			default => $type->getName(),
		};

		return match (true) {
			is_subclass_of($type, Entity::class) => new EntityCodec($type),
			is_subclass_of($type, Collection::class) => new CollectionCodec($type),
			is_subclass_of($type, BackedEnum::class) => new BackedEnumCodec($type),
			default => null,
		};
	}
}
