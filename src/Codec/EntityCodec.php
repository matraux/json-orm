<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\Metadata;

final readonly class EntityCodec implements Codec
{

	/**
	 * @param class-string<Entity> $class
	 */
	public function __construct(protected string $class)
	{
	}

	/**
	 * @throws CodecException
	 */
	public function encode(mixed $value, Metadata $metadata): ?Entity
	{
		if ($value !== null && !$value instanceof $this->class) {
			throw new CodecException(sprintf('%s::$%s expects %s, %s given.', $metadata->class, $metadata->name, $this->class, get_debug_type($value)));
		}

		/** @var ?Entity $value */
		return $value;
	}

	public function decode(Explorer $explorer, Metadata $metadata): ?Entity
	{
		$value = $explorer[$metadata->index];

		return $value !== null ? $this->class::fromExplorer($explorer->withIndex($metadata->index)) : null;
	}

}
