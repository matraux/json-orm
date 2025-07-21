<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\Property;
use Matraux\JsonORMTest\Collection\ItemCollection;
use Matraux\JsonORMTest\Entity\Enum\CommonResult;

final class CommonEntity extends Entity
{

	#[Property('NAME')]
	public ?string $name = null;

	#[Property('STATUS')]
	public ?StatusEntity $status = null;

	#[Property('ITEMS')]
	public ItemCollection $items;

	#[Property('RESULT')]
	public CommonResult $result;

}
