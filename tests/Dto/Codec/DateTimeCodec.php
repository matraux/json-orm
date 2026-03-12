<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Codec;

use DateTime;
use Matraux\JsonOrm\Codec\Codec;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\Metadata;

final class DateTimeCodec implements Codec
{
	protected const Format = 'd.m.Y H:i:s.u';

	public function encode($value, Metadata $metadata): ?string
	{
		return $value instanceof DateTime ? $value->format(self::Format) : null;
	}

	public function decode(Explorer $explorer, Metadata $metadata): ?DateTime
	{
		$value = $explorer[$metadata->index];
		if (!is_string($value)) {
			return null;
		}

		return DateTime::createFromFormat(self::Format, $value) ?: null;
	}
}
