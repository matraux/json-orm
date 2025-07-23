<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Metadata;

use Matraux\JsonORM\Exception\PropertyMetadataException;
use Matraux\JsonORM\Json\JsonProperty;
use Nette\Utils\Type;
use ReflectionAttribute;
use ReflectionProperty;

final class PropertyMetadata
{

	public readonly string $name;

	public readonly ?string $className;

	protected function __construct(protected readonly ReflectionProperty $reflection)
	{
		$attributes = $this->reflection->getAttributes(JsonProperty::class, ReflectionAttribute::IS_INSTANCEOF);
		$this->name = array_shift($attributes)?->newInstance()->name ?? $this->reflection->name;

		$type = Type::fromReflection($this->reflection);
		$this->className = $type?->isClass() ? $type->getSingleName() : null;
	}

	public static function create(ReflectionProperty $reflection): static
	{
		return new static($reflection);
	}

	public function setValue(object $object, mixed $value): void
	{
		if ($object::class !== $this->reflection->class) {
			throw new PropertyMetadataException(sprintf(
				'Cannot set property $%s: expected %s, %s given.',
				$this->reflection->name,
				$this->reflection->class,
				$object::class
			));
		}

		$this->reflection->setValue($object, $value);
	}

}
