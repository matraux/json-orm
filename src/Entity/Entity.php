<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Entity;

use JsonException;
use JsonSerializable;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadataFactory;
use RuntimeException;
use Stringable;
use Throwable;

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
				$value = $property->codec ? $property->codec->decode($explorer, $property) : $explorer[$property->index];
				try {
					$entity->{$property->name} = $value;
				} catch (Throwable $th) {
					throw new RuntimeException(
						message: sprintf('Property %s::$%s does not accept value type "%s".', $property->class, $property->name, get_debug_type($value)),
						code: 500,
						previous: $th
					);
				}
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
		$properties = PropertyMetadataFactory::create(static::class);
		foreach ($properties as $property) {
			if (!$property->isInitialized($this)) {
				continue;
			}

			$data[$property->index] = $property->codec ? $property->codec->encode($this->{$property->name}, $property) : $this->{$property->name};
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
			flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR
		);
	}

}
