<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Collection;

use Countable;
use Stringable;
use ArrayAccess;
use Traversable;
use JsonSerializable;
use RuntimeException;
use IteratorAggregate;
use OutOfRangeException;
use UnexpectedValueException;
use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORM\Entity\Entity;

/**
 * @template TEntity of Entity
 * @implements ArrayAccess<int,TEntity>
 * @implements IteratorAggregate<int,TEntity>
 */
abstract class Collection implements Countable, ArrayAccess, JsonSerializable, Stringable, IteratorAggregate
{

	/** @var array<int,TEntity> */
	final protected array $entities = [];

	final protected function __construct(protected ?Reader $reader = null)
	{
	}

	final public static function create(?Reader $reader = null): static
	{
		/** @var static<TEntity> */
		return new static($reader);
	}

	public function getIterator(): Traversable
	{
		if($this->reader) {
			foreach($this->reader as $key => $data) {
				yield (int) $key => $this->getEntity($this->reader->withKey($key));
			}
		} else {
			foreach ($this->entities as $key => $entity) {
				yield $key => $entity;
			}
		}
	}

	final public function count(): int
	{
		return $this->reader ? count($this->reader) : count($this->entities);
	}

	final public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "%s", "%s" type given.', 'int', gettype($offset)));
		}

		return $this->reader ? isset($this->reader[$offset]) : isset($this->entities[$offset]);
	}

	/**
	 * @return TEntity
	 */
	final public function offsetGet(mixed $offset): Entity
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset "%s" is out of range.', $offset));
		}

		return $this->reader ? $this->getEntity($this->reader->withKey($offset)) : $this->entities[$offset];
	}

	final public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->validateWriting();

		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "%s", "%s" type given.', 'int', gettype($offset)));
		} elseif (!$value instanceof Entity || $value::class !== $this->getEntity()::class) {
			throw new UnexpectedValueException(sprintf('Expected value type "%s", "%s" type given.', $this->getEntity()::class, gettype($value)));
		}

		$this->entities[$offset] = $value;
	}

	final public function offsetUnset(mixed $offset): void
	{
		$this->validateWriting();

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
		$this->validateWriting();

		return $this->entities[] = $this->getEntity();
	}

	/**
	 * @return array<TEntity>
	 */
	final public function jsonSerialize(): array
	{
		$this->validateWriting();

		return $this->entities;
	}

	/**
	 * @return TEntity
	 */
	abstract protected function getEntity(?Reader $reader = null): Entity;

	final protected function validateWriting(): void
	{
		if ($this->reader) {
			throw new RuntimeException(sprintf('"%s" is in read only state, because using "%s".', static::class, Reader::class));
		}
	}

	final public function __toString(): string
	{
		return json_encode($this) ?: throw new RuntimeException(sprintf('Unable JSON serialize "%s".', static::class));
	}

}
