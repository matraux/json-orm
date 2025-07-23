<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\JsonProperty;
use Matraux\JsonORMTest\Collection\ItemCollection;
use Matraux\JsonORMTest\Entity\Enum\CommonResult;

final class CommonEntity extends Entity
{

	#[JsonProperty('NAME')]
	public ?string $name = null;

	#[JsonProperty('STATUS')]
	public ?StatusEntity $status = null;

	#[JsonProperty('ITEMS')]
	public ItemCollection $items;

	#[JsonProperty('RESULT')]
	public CommonResult $result;

}
