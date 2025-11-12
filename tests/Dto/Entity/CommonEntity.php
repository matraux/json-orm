<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\JsonProperty;
use Matraux\JsonOrmTest\Dto\Collection\ItemCollection;
use Matraux\JsonOrmTest\Dto\Entity\Enum\CommonResult;

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
