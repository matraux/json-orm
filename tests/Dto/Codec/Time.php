<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Codec;

use Attribute;
use DateTime;
use Matraux\JsonOrm\Codec\Codec;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Time implements Codec
{

	protected const string Format = 'd.m.Y H:i:s.u';

	public function encode(mixed $value): ?string
	{
		return $value instanceof DateTime ? $value->format(self::Format) : null;
	}

	public function decode(mixed $value): ?DateTime
	{
		if (!is_string($value)) {
			return null;
		}

		return DateTime::createFromFormat(self::Format, $value) ?: null;
	}

}
