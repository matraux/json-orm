<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Entity;

use BackedEnum;
use JsonSerializable;
use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadataFactory;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use PropertyHookType;
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

	final public static function fromExplorer(Explorer $explorer): static
	{
		$entity = static::create();

		$properties = PropertyMetadataFactory::create(static::class);
		foreach ($properties as $property) {
			if (isset($explorer[$property->index])) {
				$value = $explorer[$property->index];

				if ($type = $property->type) {
					if (is_array($value) && is_subclass_of($type, self::class)) {
						/** @var class-string<Entity> $type */
						$entity->{$property->name} = $type::fromExplorer($explorer->withIndex($property->index));

						continue;
					} elseif (is_array($value) && is_subclass_of($type, Collection::class)) {
						/** @var class-string<Collection<Entity>> $type */
						$entity->{$property->name} = $type::create($explorer->withIndex($property->index));

						continue;
					} elseif ((is_string($value) || is_int($value)) && is_subclass_of($type, BackedEnum::class)) {
						/** @var class-string<BackedEnum> $type */
						if ($enum = $type::tryFrom($value)) {
							$entity->{$property->name} = $enum;
						}

						continue;
					}
				}

				if ($codec = $property->codec) {
					$entity->{$property->name} = $codec->decode($value);

					continue;
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
			$reflection = new ReflectionProperty(static::class, $property->name);

			if (!$reflection->isInitialized($this) && !$reflection->getHook(PropertyHookType::Get)) {
				continue;
			}

			$data[$property->index] = $property->codec ? $property->codec->encode($this->{$property->name}) : $this->{$property->name};
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
