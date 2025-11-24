<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use DateTime;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;
use Matraux\JsonOrmTest\Dto\Codec\Time;
use Matraux\JsonOrmTest\Dto\Collection\ItemCollection;
use Matraux\JsonOrmTest\Dto\Entity\Enum\CommonResult;

final class CommonEntity extends Entity
{

	#[Property('NAME')]
	public ?string $name = null;

	#[Property('TIME')]
	#[Time]
	public ?DateTime $timestamp;

	#[Property('STATUS')]
	public ?StatusEntity $status = null;

	#[Property('ITEMS')]
	public ItemCollection $items;

	#[Property('RESULT')]
	public CommonResult $result;

}
