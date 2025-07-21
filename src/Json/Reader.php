<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Json;

use ArrayAccess;
use Countable;
use IteratorAggregate;
use Nette\Utils\FileSystem;
use Nette\Utils\Json;
use OutOfRangeException;
use RuntimeException;
use Throwable;
use Traversable;
use UnexpectedValueException;

/**
 * @implements ArrayAccess<int|string,mixed>
 * @implements IteratorAggregate<int|string,mixed>
 */
final class Reader implements ArrayAccess, IteratorAggregate, Countable
{

	/** @var array<mixed> */
	public array $data;

	/**
	 * @throws UnexpectedValueException If JSON data can not be parsed
	 */
	protected function __construct(?string $json = null)
	{
		try {
			$this->data = (array) Json::decode($json ?? '[]', true);
		} catch (Throwable $th) {
			throw new UnexpectedValueException('Failed to parse JSON data.', $th->getCode(), $th);
		}
	}

	/**
	 * @throws RuntimeException If file does not exists
	 */
	public static function fromFile(string $file): static
	{
		if (!is_file($file)) {
			throw new RuntimeException(sprintf('No such file "%s".', $file));
		}

		return self::fromJson(FileSystem::read($file));
	}

	public static function fromJson(?string $json = null): static
	{
		return new static($json);
	}

	public function count(): int
	{
		return count($this->data);
	}

	public function getIterator(): Traversable
	{
		foreach ($this->data as $key => $value) {
			yield $key => $value;
		}
	}

	public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset) && !is_string($offset)) {
			throw new UnexpectedValueException(sprintf('Expected offset type "int|string", "%s" given.', gettype($offset)));
		}

		return array_key_exists($offset, $this->data);
	}

	public function offsetGet(mixed $offset): mixed
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset "%s" is out of range.', $offset));
		}

		return $this->data[$offset];
	}

	public function offsetSet(mixed $offset, mixed $value): void
	{
		throw new RuntimeException('Reader is readonly');
	}

	public function offsetUnset(mixed $offset): void
	{
		throw new RuntimeException('Reader is readonly');
	}

	public function withKey(string|int $key): static
	{
		if (!array_key_exists($key, $this->data)) {
			throw new UnexpectedValueException(sprintf('Key "%s" not found.', $key));
		} elseif (!is_array($this->data[$key])) {
			throw new UnexpectedValueException(sprintf('Missing nested data at key "%s".', $key));
		}

		$object = new static();
		$object->data = &$this->data[$key];

		return $object;
	}

}
