**[Back](../README.md)**

# Definitions

## Entity
```php
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\JsonProperty;

final class CommonEntity extends Entity
{

	// Entity will try assign value from Data with key "NAME" to property $name
	#[JsonProperty('NAME')]
	public ?string $name = null;

	#[Property('TIME')]
	#[Time]
	public ?DateTime $time;

	#[JsonProperty('STATUS')]
	public ?StatusEntity $status = null;

	#[JsonProperty('ITEMS')]
	public ItemCollection $items;

	#[JsonProperty('RESULT')]
	public CommonResult $result; // BackedEnum

}
```

## Collection
```php
use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrmTest\Entity\CommonEntity;

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
use Attribute;
use DateTime;
use Matraux\JsonOrm\Codec\Codec;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Time implements Codec
{

	protected const string Format = 'd.m.Y H:i:s.u';

	public function encode(mixed $value): ?string
	{
		return $value instanceof DateTime ? $value->format(static::Format) : null;
	}

	public function decode(mixed $value): ?DateTime
	{
		if (!is_string($value)) {
			return null;
		}

		return DateTime::createFromFormat(static::Format, $value) ?: null;
	}

}
```