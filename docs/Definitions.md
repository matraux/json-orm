**[Back](../README.md)**

# Definitions

## Entity
```php
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;

final class CommonEntity extends Entity
{

	// Entity will try assign value from Data with key "NAME" to property $name
	#[Property('NAME')]
	public ?string $name = null;

	#[Property('TIME')]
	#[DateTimeCodec]
	public ?DateTime $time;

	#[Property('STATUS')]
	public ?StatusEntity $status = null;

	#[Property('ITEMS')]
	public ItemCollection $items;

	#[Property('RESULT')]
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
use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class DateTimeCodec implements Codec
{

	protected const string Format = 'd.m.Y H:i:s.u';

	public function encode(mixed $value, PropertyMetadata $property): ?string
	{
		return $value instanceof DateTime ? $value->format(self::Format) : null;
	}

	public function decode(Explorer $explorer, PropertyMetadata $property): ?DateTime
	{
		$value = $explorer[$property->index];
		if (!is_string($value)) {
			return null;
		}

		return DateTime::createFromFormat(self::Format, $value) ?: null;
	}

}
```