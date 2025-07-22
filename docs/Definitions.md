**[Back](../Readme.md)**

# Definitions

## Entity
```php
use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\Property;

final class CommonEntity extends Entity
{

	// Entity will try assign value from Data with key "NAME" to property $name
	#[Property('NAME')]
	public ?string $name = null;

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