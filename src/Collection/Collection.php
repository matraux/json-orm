<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Exception\ReadonlyAccessException;
use Matraux\JsonOrm\Json\Explorer;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
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

	final protected function __construct(protected ?Explorer $explorer = null)
	{
	}

	final public static function create(?Explorer $explorer = null): static
	{
		/** @var static<TEntity> */
		return new static($explorer);
	}

	final public function count(): int
	{
		return $this->explorer ? count($this->explorer) : count($this->entities);
	}

	final public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expects offset type "%s", "%s" type given.', 'int', gettype($offset)));
		}

		return $this->explorer ? isset($this->explorer[$offset]) : isset($this->entities[$offset]);
	}

	/**
	 * @return TEntity
	 */
	final public function offsetGet(mixed $offset): Entity
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset "%s" is out of range.', $offset));
		}

		return $this->explorer ? static::getEntityClass()::fromExplorer($this->explorer->withIndex($offset)) : $this->entities[$offset];
	}

	final public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->assertWritable();

		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expects offset type "%s", "%s" type given.', 'int', gettype($offset)));
		} elseif (!$value instanceof Entity || $value::class !== static::getEntityClass()) {
			throw new UnexpectedValueException(sprintf('Expects value type "%s", "%s" type given.', static::getEntityClass(), gettype($value)));
		}

		$this->entities[$offset] = $value;
	}

	final public function offsetUnset(mixed $offset): void
	{
		$this->assertWritable();

		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset "%s" is out of range.', $offset));
		}

		unset($this->entities[$offset]);
	}

	/**
	 * @return TEntity
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
		return array_values(iterator_to_array($this));
	}

	public function getIterator(): Traversable
	{
		if ($this->explorer) {
			foreach ($this->explorer as $key => $data) {
				yield (int) $key => static::getEntityClass()::fromExplorer($this->explorer->withIndex($key));
			}
		} else {
			foreach ($this->entities as $key => $entity) {
				yield $key => $entity;
			}
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
		return Json::encode($this);
	}

}
