<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Json;

use JsonException;
use OutOfRangeException;
use ReflectionClass;
use RuntimeException;
use Traversable;
use UnexpectedValueException;

final class SimpleExplorer extends Explorer
{
	/** @var int<0,max> */
	protected int $countCache;

	/** @var array<mixed> */
	protected array $data;

	/**
	 * @param array<mixed> $data
	 */
	protected function __construct(array $data) {$this->data = $data;}

	public static function fromString(string $json): self
	{
		$data = json_decode(
			$json,
			null,
			512,
			JSON_OBJECT_AS_ARRAY | JSON_BIGINT_AS_STRING | JSON_THROW_ON_ERROR,
		);

		if (!is_array($data)) {
			throw new UnexpectedValueException(sprintf('Json data expects array, %s given.', get_debug_type($data)));
		}

		return new static($data);
	}

	public static function fromFile(string $file): self
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
	 * @param mixed $offset
	 * @throws UnexpectedValueException
	 */
	public function offsetExists($offset): bool
	{
		if (!is_int($offset) && !is_string($offset)) {
			throw new UnexpectedValueException(sprintf('Offset expects int|string, %s given.', get_debug_type($offset)));
		}

		return array_key_exists($offset, $this->data);
	}

	/**
	 * @param int|string $offset
	 * @return mixed
	 * @throws OutOfRangeException
	 * @throws UnexpectedValueException
	 */
	public function offsetGet($offset)
	{
		if (!$this->offsetExists($offset)) {
			throw new OutOfRangeException(sprintf('Offset %s is out of range.', $offset));
		}

		return $this->data[$offset];
	}

	/**
	 * @param string|int $index
	 * @throws UnexpectedValueException
	 */
	public function withIndex($index): self
	{
		if(!is_int($index) && !is_string($index)) {
			throw new UnexpectedValueException(sprintf('Expects value string|int, %s given.', get_debug_type($index)));
		} elseif (!array_key_exists($index, $this->data)) {
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
			$this->data,
			JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRESERVE_ZERO_FRACTION | JSON_THROW_ON_ERROR,
		);
	}
}
