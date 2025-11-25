<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Codec;

use Attribute;
use DateTime;
use Matraux\JsonOrm\Codec\Codec;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class DateTimeCodec implements Codec
{

	protected const string Format = 'd.m.Y H:i:s.u';

	public function encode(mixed $value, PropertyMetadata $property): ?string
	{
		return $value instanceof DateTime ? $value->format(self::Format) : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?DateTime
	{
		$value = $explorer[$property->index];
		if (!is_string($value)) {
			return null;
		}

		return DateTime::createFromFormat(self::Format, $value) ?: null;
	}

}
