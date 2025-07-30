<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Entity;

use BackedEnum;
use JsonSerializable;
use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\JsonReader;
use Matraux\JsonORM\Metadata\PropertyMetadataFactory;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use ReflectionException;
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
			if ($reader->offsetExists($property->index)) {
				$value = $reader[$property->index];

				if ($type = $property->type) {
					if (is_array($value) && is_subclass_of($type, self::class)) {
						/** @var class-string<Entity> $type */
						$entity = $type::fromReader($reader->withKey($property->index));
						$object->{$property->name} = $entity;
					} elseif (is_array($value) && is_subclass_of($type, Collection::class)) {
						/** @var class-string<Collection<Entity>> $type */
						$collection = $type::create($reader->withKey($property->index));
						$object->{$property->name} = $collection;
					} elseif ((is_string($value) || is_int($value)) && is_subclass_of($type, BackedEnum::class)) {
						/** @var class-string<BackedEnum> $type */
						if ($enum = $type::tryFrom($value)) {
							$object->{$property->name} = $enum;
						}
					}
				} elseif (is_scalar($value) || $value === null) {
					$object->{$property->name} = $value;
				}
			}
		}

		return $object;
	}

	/**
	 * @return array<mixed>
	 * @throws ReflectionException
	 */
	final public function jsonSerialize(): array
	{
		$data = [];
		$properties = PropertyMetadataFactory::create(static::class);
		foreach ($properties as $property) {
			if (!new ReflectionProperty(static::class, $property->name)->isInitialized($this)) {
				continue;
			}

			$data[$property->index] = $this->{$property->name};
		}

		return $data;
	}

	/**
	 * @throws JsonException
	 */
	final public function __toString(): string
	{
		return Json::encode($this);
	}

}
