<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Entity;

use JsonException;
use JsonSerializable;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\MetadataFactory;
use Stringable;

abstract class Entity implements Stringable, JsonSerializable
{
	final protected function __construct() {}

	final public static function create(): static
	{
		return new static();
	}

	final public static function fromExplorer(Explorer $explorer): static
	{

		$entity = new static();
		$properties = MetadataFactory::create(static::class);
		foreach ($properties as $property) {
			$name = $property->name;
			$index = $property->index;

			if ($explorer->offsetExists($index)) {
				$entity->{$name} = $property->codec ? $property->codec->decode($explorer, $property) : $explorer[$index];
			}
		}

		return $entity;
	}

	/**
	 * @return array<mixed>
	 */
	final public function jsonSerialize(): array
	{
		$data = [];
		$properties = MetadataFactory::create(static::class);
		foreach ($properties as $property) {
			if (!$property->isInitialized($this)) {
				continue;
			}

			$name = $property->name;
			$index = $property->index;

			$data[$index] = $property->codec ? $property->codec->encode($this->{$name}, $property) : $this->{$name};
		}

		return $data;
	}

	/**
	 * @throws JsonException
	 */
	final public function __toString(): string
	{
		return json_encode(
			value: $this,
			flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR,
		);
	}
}
