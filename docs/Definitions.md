**[Back](../README.md)**

# Definitions

## Entity
```php
use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\JsonProperty;

final class CommonEntity extends Entity
{

	// Entity will try assign value from Data with key "NAME" to property $name
	#[JsonProperty('NAME')]
	public ?string $name = null;

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
use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORMTest\Entity\CommonEntity;

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