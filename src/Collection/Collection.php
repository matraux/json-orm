<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonException;
use JsonSerializable;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Exception\ReadonlyAccessException;
use Matraux\JsonOrm\Json\Explorer;
use OutOfRangeException;
use Stringable;
use Traversable;
use UnexpectedValueException;

/**
 * @template TEntity of Entity
 * @implements ArrayAccess<int,TEntity>
 * @implements IteratorAggregate<int,TEntity>
 */
abstract class Collection implements Countable, ArrayAccess, JsonSerializable, Stringable, IteratorAggregate
{
	/** @var array<int,TEntity> */
	final protected array $entities = [];

	final protected function __construct(protected readonly ?Explorer $explorer = null) {}

	/**
	 * @return static<TEntity>
	 */
	final public static function create(): static
	{
		/** @var static<TEntity> */
		return new static();
	}

	/**
	 * @return static<TEntity>
	 */
	final public static function fromExplorer(Explorer $explorer): static
	{
		/** @var static<TEntity> */
		return new static($explorer);
	}

	final public function count(): int
	{
		return $this->explorer ? count($this->explorer) : count($this->entities);
	}

	/**
	 * @throws UnexpectedValueException
	 */
	final public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Offset expects int, %s given.', get_debug_type($offset)));
		}

		return $this->explorer ? isset($this->explorer[$offset]) : isset($this->entities[$offset]);
	}

	/**
	 * @return TEntity
	 * @throws OutOfRangeException
	 * @throws UnexpectedValueException
	 */
	final public function offsetGet(mixed $offset): Entity
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset %s is out of range.', $offset));
		}

		return $this->explorer ? static::getEntityClass()::fromExplorer($this->explorer->withIndex($offset)) : $this->entities[$offset];
	}

	/**
	 * @throws UnexpectedValueException
	 * @throws ReadonlyAccessException
	 */
	final public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->assertWritable();

		if (!is_int($offset) && $offset !== null) {
			throw new UnexpectedValueException(sprintf('Offset expects int, %s given.', get_debug_type($offset)));
		} elseif (!$value instanceof Entity || $value::class !== static::getEntityClass()) {
			throw new UnexpectedValueException(sprintf('Offset expects %s, %s given.', static::getEntityClass(), get_debug_type($value)));
		}

		$offset !== null ? $this->entities[$offset] = $value : $this->entities[] = $value;
	}

	/**
	 * @throws OutOfRangeException
	 * @throws UnexpectedValueException
	 * @throws ReadonlyAccessException
	 */
	final public function offsetUnset(mixed $offset): void
	{
		$this->assertWritable();

		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset %s is out of range.', $offset));
		}

		unset($this->entities[$offset]);
	}

	/**
	 * @return TEntity
	 * @throws ReadonlyAccessException
	 */
	final public function createEntity(): Entity
	{
		$this->assertWritable();

		return $this->entities[] = static::getEntityClass()::create();
	}

	/**
	 * @return array<TEntity>
	 */
	final public function jsonSerialize(): array
	{
		$entities = [];
		foreach ($this as $entity) {
			$entities[] = $entity;
		}

		return $entities;
	}

	/**
	 * @return Traversable<int,TEntity>
	 */
	public function getIterator(): Traversable
	{
		if ($this->explorer) {
			foreach ($this->explorer as $index => $_) {
				if (!is_int($index)) {
					throw new UnexpectedValueException(sprintf('Collection expects int index, %s given.', get_debug_type($index)));
				}

				yield $index => static::getEntityClass()::fromExplorer($this->explorer->withIndex($index));
			}
		} else {
			yield from $this->entities;
		}
	}

	/**
	 * @return class-string<TEntity>
	 */
	abstract protected static function getEntityClass(): string;

	final protected function assertWritable(): void
	{
		if ($this->explorer) {
			throw new ReadonlyAccessException('Collection is readonly.');
		}
	}

	/**
	 * @throws JsonException
	 */
	final public function __toString(): string
	{
		return json_encode(
			value: $this,
			flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR,
		);
	}
}
