<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Entity;

use JsonSerializable;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadataFactory;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
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
				$entity->{$property->name} = $property->codec ? $property->codec->decode($explorer, $property) : $explorer[$property->index];
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
		return Json::encode($this);
	}

}
