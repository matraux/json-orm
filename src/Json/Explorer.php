<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Json;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Matraux\JsonOrm\Exception\ReadonlyAccessException;
use Stringable;

/**
 * @implements ArrayAccess<int|string,mixed>
 * @implements IteratorAggregate<int|string,mixed>
 */
abstract class Explorer implements ArrayAccess, IteratorAggregate, Countable
{
	/**
	 * @param string|int $index
	 */
	abstract public function withIndex($index): self;

	/**
	 *
	 * @param int|string $offset
	 * @param mixed $value
	 * @throws ReadonlyAccessException
	 */
	final public function offsetSet($offset, $value): void
	{
		throw new ReadonlyAccessException('Explorer is readonly');
	}

	/**
	 *
	 * @param int|string $offset
	 * @throws ReadonlyAccessException
	 */
	final public function offsetUnset($offset): void
	{
		throw new ReadonlyAccessException('Explorer is readonly');
	}

	abstract public function __toString(): string;
}
