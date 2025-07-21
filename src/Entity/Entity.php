<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Entity;

use BackedEnum;
use JsonSerializable;
use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\Property;
use Matraux\JsonORM\Json\Reader;
use Nette\Utils\Type;
use ReflectionAttribute;
use ReflectionObject;
use ReflectionProperty;
use RuntimeException;
use Stringable;

abstract class Entity implements Stringable, JsonSerializable
{

	final protected function __construct(protected ?Reader $reader = null)
	{
		if ($reader) {
			$reflection = new ReflectionObject($this);
			foreach ($reflection->getProperties() as $property) {
				$propertyName = self::getProperty($property)->name ?? $property->getName();

				if ($reader->offsetExists($propertyName)) {
					if (!$type = Type::fromReflection($property)) {
						continue;
					}

					$value = $reader[$propertyName];
					$className = $type->isClass() && $type->isSimple() ? $type->getSingleName() : null;

					if ($className) {
						if (is_array($value) && is_subclass_of($className, self::class)) {
							/** @var class-string<Entity> $className */
							$entity = $className::create($reader->withKey($propertyName));
							$property->setValue($this, $entity);
						} elseif (is_array($value) && is_subclass_of($className, Collection::class)) {
							/** @var class-string<Collection<Entity>> $className */
							$collection = $className::create($reader->withKey($propertyName));
							$property->setValue($this, $collection);
						} elseif ((is_string($value) || is_int($value)) && is_subclass_of($className, BackedEnum::class)) {
							/** @var class-string<BackedEnum> $className */
							$enum = $className::tryFrom($value);
							$property->setValue($this, $enum);
						}
					} elseif (is_scalar($value) || $value === null) {
						$property->setValue($this, $value);
					}
				}
			}
		}
	}

	final public static function create(?Reader $reader = null): static
	{
		return new static($reader);
	}

	/**
	 * @return array<mixed>
	 */
	final public function jsonSerialize(): array
	{
		$this->validateWriting();

		$data = [];
		$properties = (new ReflectionObject($this))->getProperties(ReflectionProperty::IS_PUBLIC);
		foreach ($properties as $property) {
			if (!$property->isInitialized($this)) {
				continue;
			}

			$name = self::getProperty($property)->name ?? $property->getName();
			$data[$name] = $property->getValue($this);
		}

		return $data;
	}

	final protected static function getProperty(ReflectionProperty|ReflectionObject $property): ?Property
	{
		$attributes = $property->getAttributes(Property::class, ReflectionAttribute::IS_INSTANCEOF);

		return array_shift($attributes)?->newInstance();
	}

	/**
	 * @throws RuntimeException If Entity using Data
	 */
	final protected function validateWriting(): void
	{
		if ($this->reader) {
			throw new RuntimeException(sprintf('"%s" is in read only state, because using "%s".', static::class, Reader::class));
		}
	}

	/**
	 * @throws RuntimeException If entity can not be serialized to JSON
	 */
	final public function __toString(): string
	{
		return json_encode($this) ?: throw new RuntimeException(sprintf('Unable JSON serialize "%s".', static::class));
	}

}
