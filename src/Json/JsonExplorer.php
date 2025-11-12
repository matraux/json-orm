<?php declare(strict_types = 1);

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
abstract class JsonExplorer implements ArrayAccess, IteratorAggregate, Countable, Stringable
{

	abstract public function withIndex(string|int $index): static;

	/**
	 * @throws ReadonlyAccessException
	 */
	final public function offsetSet(mixed $offset, mixed $value): void
	{
		throw new ReadonlyAccessException('Explorer is readonly');
	}

	/**
	 * @throws ReadonlyAccessException
	 */
	final public function offsetUnset(mixed $offset): void
	{
		throw new ReadonlyAccessException('Explorer is readonly');
	}

}
