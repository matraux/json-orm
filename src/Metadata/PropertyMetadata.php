<?php declare(strict_types = 1);

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
use Nette\Utils\Type;
use PropertyHookType;
use ReflectionAttribute;
use ReflectionProperty;

final readonly class PropertyMetadata
{

	public string $name;

	public string $index;

	public ?string $type;

	public ?Codec $codec;

	protected function __construct(protected readonly ReflectionProperty $reflection)
	{
		$this->name = $this->reflection->name;

		$attributes = $this->reflection->getAttributes(Property::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->index = array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;

		$type = Type::fromReflection($this->reflection);
		$this->type = $type?->isClass() ? $type->getSingleName() : null;

		$attributes = $this->reflection->getAttributes(Codec::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new CodecException(sprintf('Property %s::$%s expects single %s attribute, multiple given.', $this->reflection->getDeclaringClass()->getName(), $this->name, Codec::class));
		}

		if ($codec = array_shift($attributes)?->newInstance()) {
			$this->codec = $codec;
		} elseif ($this->type && is_subclass_of($this->type, Entity::class)) {
			$this->codec = new EntityCodec();
		} elseif ($this->type && is_subclass_of($this->type, Collection::class)) {
			$this->codec = new CollectionCodec();
		} elseif ($this->type && is_subclass_of($this->type, BackedEnum::class)) {
			$this->codec = new BackedEnumCodec();
		} else {
			$this->codec = null;
		}
	}

	/**
	 * @throws CodecException
	 */
	public static function create(ReflectionProperty $reflection): static
	{
		return new static($reflection);
	}

	public function isInitialized(Entity $entity): bool
	{
		return $this->reflection->isInitialized($entity) || $this->reflection->getHook(PropertyHookType::Get);
	}

}
