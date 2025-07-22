<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Entity;

use BackedEnum;
use JsonSerializable;
use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\Property;
use Matraux\JsonORM\Json\Reader;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use Nette\Utils\Type;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;
use Stringable;

abstract class Entity implements Stringable, JsonSerializable
{

	final protected function __construct()
	{
	}

	final public static function create(): static
	{
		return new static();
	}

	final public static function fromReader(Reader $reader): static
	{
		$object = static::create();

		$properties = new ReflectionClass(static::class)->getProperties();
		foreach ($properties as $property) {
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
						$entity = $className::fromReader($reader->withKey($propertyName));
						$property->setValue($object, $entity);
					} elseif (is_array($value) && is_subclass_of($className, Collection::class)) {
						/** @var class-string<Collection<Entity>> $className */
						$collection = $className::create($reader->withKey($propertyName));
						$property->setValue($object, $collection);
					} elseif ((is_string($value) || is_int($value)) && is_subclass_of($className, BackedEnum::class)) {
						/** @var class-string<BackedEnum> $className */
						$enum = $className::tryFrom($value);
						$property->setValue($object, $enum);
					}
				} elseif (is_scalar($value) || $value === null) {
					$property->setValue($object, $value);
				}
			}
		}

		return $object;
	}

	/**
	 * @return array<mixed>
	 */
	final public function jsonSerialize(): array
	{
		$data = [];
		$properties = new ReflectionObject($this)->getProperties(ReflectionProperty::IS_PUBLIC);
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
	 * @throws JsonException
	 */
	final public function __toString(): string
	{
		return Json::encode($this);
	}

}
