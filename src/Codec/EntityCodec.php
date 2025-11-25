<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

final class EntityCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): mixed
	{
		return $value instanceof Entity ? $value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?Entity
	{
		$type = $property->type;
		if (!$type || !is_subclass_of($type, Entity::class)) {
			return null;
		}

		/** @var class-string<Entity> $type */
		return $type::fromExplorer($explorer->withIndex($property->index));
	}

}
