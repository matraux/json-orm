<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Dto\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\JsonProperty;
use Matraux\JsonORMTest\Dto\Collection\ItemCollection;
use Matraux\JsonORMTest\Dto\Entity\Enum\CommonResult;

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
