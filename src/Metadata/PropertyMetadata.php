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
use PropertyHookType;
use ReflectionAttribute;
use ReflectionIntersectionType;
use ReflectionNamedType;
use ReflectionProperty;
use ReflectionType;
use ReflectionUnionType;
use RuntimeException;

final readonly class PropertyMetadata
{

	public string $name;

	public string $class;

	public string $index;

	public ?string $type;

	public ?Codec $codec;

	/** @var array<string|class-string> */
	public array $types;

	/**
	 * @throws CodecException
	 */
	public function __construct(protected readonly ReflectionProperty $reflection)
	{
		$this->name = $this->reflection->name;
		$this->class = $this->reflection->class;
		$this->index = $this->resolveIndex();
		$this->type = $this->resolveType();
		$this->types = $this->resolveTypes();
		$this->codec = $this->resolveCodec();
	}

	public function isInitialized(Entity $entity): bool
	{
		return $this->reflection->isInitialized($entity) || $this->reflection->getHook(PropertyHookType::Get);
	}

	protected function resolveType(): ?string
	{
		$type = $this->reflection->getType();

		return $type instanceof ReflectionNamedType ? $type->getName() : null;
	}

	protected function resolveCodec(): ?Codec
	{
		$attributes = $this->reflection->getAttributes(Codec::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new CodecException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->class, $this->name, Codec::class));
		}

		if ($codec = array_shift($attributes)?->newInstance()) {
			return $codec;
		}

		if ($this->type) {
			return match (true) {
				is_subclass_of($this->type, Entity::class) => new EntityCodec(),
				is_subclass_of($this->type, Collection::class) => new CollectionCodec(),
				is_subclass_of($this->type, BackedEnum::class) => new BackedEnumCodec(),
				default => null,
			};
		}

		return null;
	}

	protected function resolveIndex(): string
	{
		$attributes = $this->reflection->getAttributes(Property::class, ReflectionAttribute::IS_INSTANCEOF);
		if (count($attributes) > 1) {
			throw new RuntimeException(sprintf('%s::$%s expects single %s attribute, multiple given.', $this->class, $this->name, Property::class));
		}

		return array_shift($attributes)?->newInstance()->name ?? $this->name;
	}

	/**
	 * @return array<string|class-string>
	 */
	protected function resolveTypes(?ReflectionType $type = null): array
	{
		$type ??= $this->reflection->getType();

		$types = [];
		if ($type instanceof ReflectionUnionType || $type instanceof ReflectionIntersectionType) {
			foreach ($type->getTypes() as $subType) {
				$types = array_merge($types, $this->resolveTypes($subType));
			}
		} elseif ($type instanceof ReflectionNamedType) {
			$name = $type->getName();
			$lower = strtolower($name);

			if ($type->allowsNull()) {
				$types['null'] = 'null';
			}

			if ($lower === 'self') {
				$name = $this->class;
				$types[$name] = $name;
			} elseif ($lower === 'parent' && $parent = $this->reflection->getDeclaringClass()->getParentClass()) {
				$name = $parent->name;
				$types[$name] = $name;
			} else {
				$types[$name] = $name;
			}
		}

		return array_unique($types);
	}

}
