<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

use Attribute;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class EntityCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): ?Entity
	{
		return $value instanceof Entity ? $value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?Entity
	{
		/** @var ?class-string<Entity> $type */
		$type = $property->type;
		if (!$type || !is_subclass_of($type, Entity::class)) {
			return null;
		}

		return $type::fromExplorer($explorer->withIndex($property->index));
	}

}
