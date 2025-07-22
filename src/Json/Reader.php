<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Json;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Matraux\JsonORM\Exception\ReadonlyAccessException;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use OutOfRangeException;
use RuntimeException;
use Stringable;
use Traversable;
use UnexpectedValueException;

/**
 * @implements ArrayAccess<int|string,mixed>
 * @implements IteratorAggregate<int|string,mixed>
 */
final class Reader implements ArrayAccess, IteratorAggregate, Countable, Stringable
{

	/** @var int<0,max> */
	protected int $countCache;

	/**
	 * @param array<mixed> $data
	 */
	protected function __construct(protected array $data)
	{
	}

	/**
	 * @throws JsonException
	 */
	public static function fromString(?string $json = null): static
	{
		$data = (array) Json::decode($json ?? '[]', true);

		return new static($data);
	}

	/**
	 * @throws RuntimeException
	 */
	public static function fromFile(string $file): static
	{
		if (!is_file($file)) {
			throw new RuntimeException(sprintf('No such file "%s".', $file));
		}

		return self::fromString(FileSystem::read($file));
	}

	public function count(): int
	{
		return $this->countCache ??= count($this->data);
	}

	public function getIterator(): Traversable
	{
		foreach ($this->data as $key => $value) {
			yield $key => $value;
		}
	}

	/**
	 * @throws UnexpectedValueException
	 */
	public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset) && !is_string($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "int|string", "%s" given.', gettype($offset)));
		}

		return array_key_exists($offset, $this->data);
	}

	/**
	 * @throws OutOfRangeException
	 */
	public function offsetGet(mixed $offset): mixed
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset "%s" is out of range.', $offset));
		}

		return $this->data[$offset];
	}

	/**
	 * @throws ReadonlyAccessException
	 */
	public function offsetSet(mixed $offset, mixed $value): void
	{
		throw new ReadonlyAccessException('Reader is readonly');
	}

	/**
	 * @throws ReadonlyAccessException
	 */
	public function offsetUnset(mixed $offset): void
	{
		throw new ReadonlyAccessException('Reader is readonly');
	}

	/**
	 * @throws UnexpectedValueException
	 */
	public function withKey(string|int $key): static
	{
		if (!array_key_exists($key, $this->data)) {
			throw new UnexpectedValueException(sprintf('Missing required key "%s".', $key));
		} elseif (!is_array($this->data[$key])) {
			throw new UnexpectedValueException(sprintf('Missing nested data at key "%s".', $key));
		}

		return new static($this->data[$key]);
	}

	/**
	 * @throws JsonException
	 */
	public function __toString(): string
	{
		return Json::encode($this->data);
	}

}
