**[Back](../README.md)**

# Definitions

## Entity
```php
use Matraux\JsonOrm\Entity\Entity;

final class CommonEntity extends Entity
{
	/**
	 * Entity will try assign value from Data with key "NAME" to property $name
	 * @index NAME
	 */
	public ?string $name = null;

	/**
	 * @index TIME
	 * @codec Matraux\JsonOrm\Test\Dto\Codec\DateTimeCodec
	 */
	public ?DateTime $timestamp;

	/**
	 * @index STATUS
	 */
	public ?StatusEntity $status = null;

	/**
	 * @index ITEMS
	 */
	public ItemCollection $items;

	/**
	 * @index RESULT
	 */
	public string $result;
}
```

## Collection
```php
use Matraux\JsonOrm\Collection\Collection;

/**
 * @extends Collection<CommonEntity>
 */
final class CommonCollection extends Collection
{

	protected static function getEntityClass(): string
	{
		return CommonEntity::class;
	}

}
```

## Codec
```php
use DateTime;
use Matraux\JsonOrm\Codec\Codec;
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\Metadata;

final class DateTimeCodec implements Codec
{
	protected const Format = 'd.m.Y H:i:s.u';

	public function encode($value, Metadata $metadata): ?string
	{
		return $value instanceof DateTime ? $value->format(self::Format) : null;
	}

	public function decode(Explorer $explorer, Metadata $metadata): ?DateTime
	{
		$value = $explorer[$metadata->index];
		if (!is_string($value)) {
			return null;
		}

		return DateTime::createFromFormat(self::Format, $value) ?: null;
	}
}
```