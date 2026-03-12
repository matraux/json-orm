<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Codec;

use BackedEnum;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\Metadata;
use TypeError;
use ValueError;

final class BackedEnumCodec implements Codec
{
	/**
	 * @param class-string<BackedEnum> $class
	 */
	public function __construct(protected string $class) {}

	/**
	 * @throws CodecException
	 */
	public function encode(mixed $value, Metadata $metadata): int|string|null
	{
		if ($value !== null && !$value instanceof $this->class) {
			throw new CodecException(sprintf('%s::$%s expects %s, %s given.', $metadata->class, $metadata->name, $this->class, get_debug_type($value)));
		}

		/** @var ?BackedEnum $value */
		return $value?->value;
	}

	/**
	 * @throws CodecException
	 * @throws ValueError
	 * @throws TypeError
	 */
	public function decode(Explorer $explorer, Metadata $metadata): ?BackedEnum
	{
		$value = $explorer[$metadata->index];
		if (!is_int($value) && !is_string($value) && $value !== null) {
			throw new CodecException(sprintf('%s::$%s expects null|int|string, %s given.', $metadata->class, $metadata->name, get_debug_type($value)));
		}

		return $value !== null ? $this->class::from($value) : null;
	}
}
