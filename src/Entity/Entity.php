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
		$entity = static::create();

		$properties = PropertyMetadataFactory::create(static::class);
		foreach ($properties as $property) {
			if (isset($reader[$property->index])) {
				$value = $reader[$property->index];

				if ($type = $property->type) {
					if (is_array($value) && is_subclass_of($type, self::class)) {
						/** @var class-string<Entity> $type */
						$entity->{$property->name} = $type::fromReader($reader->withKey($property->index));

						continue;
					} elseif (is_array($value) && is_subclass_of($type, Collection::class)) {
						/** @var class-string<Collection<Entity>> $type */
						$entity->{$property->name} = $type::create($reader->withKey($property->index));

						continue;
					} elseif ((is_string($value) || is_int($value)) && is_subclass_of($type, BackedEnum::class)) {
						/** @var class-string<BackedEnum> $type */
						if ($enum = $type::tryFrom($value)) {
							$entity->{$property->name} = $enum;
						}

						continue;
					}

					settype($value, $type);
				}

				$entity->{$property->name} = $value;
			}
		}

		return $entity;
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
