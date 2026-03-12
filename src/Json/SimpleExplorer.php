<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Json;

use JsonException;
use OutOfRangeException;
use RuntimeException;
use Traversable;
use UnexpectedValueException;

final class SimpleExplorer extends Explorer
{
	/** @var int<0,max> */
	protected int $countCache;

	/**
	 * @param array<mixed> $data
	 */
	protected function __construct(protected readonly array $data) {}

	public static function fromString(string $json): static
	{
		$data = json_decode(
			json: $json,
			flags: JSON_OBJECT_AS_ARRAY | JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR,
		);

		if (!is_array($data)) {
			throw new UnexpectedValueException(sprintf('Json data expects array, %s given.', get_debug_type($data)));
		}

		return new static($data);
	}

	public static function fromFile(string $file): static
	{
		if (!is_file($file)) {
			throw new RuntimeException(sprintf('No such file %s.', $file));
		} elseif (($json = @file_get_contents($file)) === false) {
			throw new RuntimeException(sprintf('Can not open file %s.', $file));
		}

		return self::fromString($json);
	}

	public function count(): int
	{
		return $this->countCache ??= count($this->data);
	}

	/**
	 * @return Traversable<mixed>
	 */
	public function getIterator(): Traversable
	{
		yield from $this->data;
	}

	/**
	 * @throws UnexpectedValueException
	 */
	public function offsetExists(mixed $offset): bool
	{
		if (!is_int($offset) && !is_string($offset)) {
			throw new UnexpectedValueException(sprintf('Offset expects int|string, %s given.', get_debug_type($offset)));
		}

		return array_key_exists($offset, $this->data);
	}

	/**
	 * @throws OutOfRangeException
	 * @throws UnexpectedValueException
	 */
	public function offsetGet(mixed $offset): mixed
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset %s is out of range.', $offset));
		}

		return $this->data[$offset];
	}

	/**
	 * @throws UnexpectedValueException
	 */
	public function withIndex(string|int $index): static
	{
		if (!array_key_exists($index, $this->data)) {
			throw new UnexpectedValueException(sprintf('No such index %s.', $index));
		} elseif (!is_array($this->data[$index])) {
			throw new UnexpectedValueException(sprintf('No such nested data at index %s.', $index));
		}

		return new static($this->data[$index]);
	}

	/**
	 * @throws JsonException
	 */
	public function __toString(): string
	{
		return json_encode(
			value: $this->data,
			flags: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR,
		);
	}
}
