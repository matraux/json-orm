<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Codec;

use Exception;
use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Exception\CodecException;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\Metadata;
use RuntimeException;

final class CollectionCodec implements Codec
{

	/** @var class-string<Collection<Entity>> */
	protected string $class;

	/**
	 * @param class-string<Collection<Entity>> $class
	 */
	public function __construct(string $class) {$this->class = $class;}

	/**
	 * @param mixed $value
	 * @return ?Collection<Entity>
	 * @throws CodecException
	 */
	public function encode($value, Metadata $metadata): ?Collection
	{
		if ($value !== null && !$value instanceof $this->class) {
			throw new CodecException(sprintf('%s::$%s expects %s, %s given.', $metadata->class, $metadata->name, $this->class, get_debug_type($value)));
		}

		return $value;
	}

	/**
	 * @return ?Collection<Entity>
	 */
	public function decode(Explorer $explorer, Metadata $metadata): ?Collection
	{
		$value = $explorer[$metadata->index];

		return $value !== null ? $this->class::fromExplorer($explorer->withIndex($metadata->index)) : null;
	}
}
