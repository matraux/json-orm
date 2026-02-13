<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

use Attribute;
use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class CollectionCodec implements Codec
{

	/**
	 * @return Collection<Entity>
	 */
	public function encode(mixed $value, PropertyMetadata $property): ?Collection
	{
		return $value instanceof Collection ? $value : null;
	}

	/**
	 * @return Collection<Entity>
	 */
	public function decode(Explorer $explorer, PropertyMetadata $property): ?Collection
	{
		/** @var ?class-string<Collection<Entity>> $type */
		$type = $property->type;
		if (!$type || !is_subclass_of($type, Collection::class)) {
			return null;
		}

		return $type::fromExplorer($explorer->withIndex($property->index));
	}

}
