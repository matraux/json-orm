<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Collection;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Exception\ReadonlyAccessException;
use Matraux\JsonORM\Json\Reader;
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

	final protected function __construct(protected ?Reader $reader = null)
	{
	}

	final public static function create(?Reader $reader = null): static
	{
		/** @var static<TEntity> */
		return new static($reader);
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

		return $this->reader ? static::getEntityClass()::fromReader($this->reader->withKey($offset)) : $this->entities[$offset];
	}

	final public function offsetSet(mixed $offset, mixed $value): void
	{
		$this->assertWritable();

		if (!is_int($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "%s", "%s" type given.', 'int', gettype($offset)));
		} elseif (!$value instanceof Entity || $value::class !== static::getEntityClass()) {
			throw new UnexpectedValueException(sprintf('Expected value type "%s", "%s" type given.', static::getEntityClass(), gettype($value)));
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
		return iterator_to_array($this);
	}

	public function getIterator(): Traversable
	{
		if ($this->reader) {
			foreach ($this->reader as $key => $data) {
				yield (int) $key => static::getEntityClass()::fromReader($this->reader->withKey($key));
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
		if ($this->reader) {
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
