<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Test\Dto\Entity;

use DateTime;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;
use Matraux\JsonOrm\Test\Dto\Codec\DateTimeCodec;
use Matraux\JsonOrm\Test\Dto\Collection\ItemCollection;
use Matraux\JsonOrm\Test\Dto\Enum\CommonResult;

final class CommonEntity extends Entity
{

	#[Property('NAME')]
	public ?string $name = null;

	#[Property('TIME')]
	#[DateTimeCodec]
	public ?DateTime $timestamp;

	#[Property('STATUS')]
	public ?StatusEntity $status = null;

	#[Property('ITEMS')]
	public ItemCollection $items;

	#[Property('RESULT')]
	public CommonResult $result;

}
