<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

use Attribute;
use BackedEnum;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class BackedEnumCodec implements Codec
{

	public function encode(mixed $value, PropertyMetadata $property): null|int|string
	{
		return $value instanceof BackedEnum ? $value->value : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?BackedEnum
	{
		/** @var ?class-string<BackedEnum> $type */
		$type = $property->type;
		if (!$type || !is_subclass_of($type, BackedEnum::class)) {
			return null;
		}

		$value = $explorer[$property->index];
		if (!is_int($value) && !is_string($value)) {
			return null;
		}

		return $type::from($value);
	}

}
