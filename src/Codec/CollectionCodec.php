<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

final class CollectionCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): mixed
	{
		return $value instanceof Collection ? $value : null;
	}

	/**
	 * @return Collection<Entity>|null
	 */
	public function decode(Explorer $explorer, PropertyMetadata $property): ?Collection
	{
		$type = $property->type;
		if (!$type || !is_subclass_of($type, Collection::class)) {
			return null;
		}

		/** @var class-string<Collection<Entity>> $type */
		return $type::fromExplorer($explorer->withIndex($property->index));
	}

}
