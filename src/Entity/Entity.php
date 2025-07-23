<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Entity;

use BackedEnum;
use JsonSerializable;
use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\JsonProperty;
use Matraux\JsonORM\Json\JsonReader;
use Matraux\JsonORM\Metadata\PropertyMetadataFactory;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use ReflectionAttribute;
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

	final public static function fromReader(JsonReader $reader): static
	{
		$object = static::create();

		$properties = PropertyMetadataFactory::create(static::class);
		foreach ($properties as $property) {
			if ($reader->offsetExists($property->name)) {
				$value = $reader[$property->name];

				if ($className = $property->className) {
					if (is_array($value) && is_subclass_of($className, self::class)) {
						/** @var class-string<Entity> $className */
						$entity = $className::fromReader($reader->withKey($property->name));
						$property->setValue($object, $entity);
					} elseif (is_array($value) && is_subclass_of($className, Collection::class)) {
						/** @var class-string<Collection<Entity>> $className */
						$collection = $className::create($reader->withKey($property->name));
						$property->setValue($object, $collection);
					} elseif ((is_string($value) || is_int($value)) && is_subclass_of($className, BackedEnum::class)) {
						/** @var class-string<BackedEnum> $className */
						if ($enum = $className::tryFrom($value)) {
							$property->setValue($object, $enum);
						}
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

	final protected static function getProperty(ReflectionProperty|ReflectionObject $property): ?JsonProperty
	{
		$attributes = $property->getAttributes(JsonProperty::class, ReflectionAttribute::IS_INSTANCEOF);

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
