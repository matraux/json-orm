<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Entity;

use DateTime;
use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;
use Matraux\JsonOrm\Test\Dto\Codec\DateTimeCodec;
use Matraux\JsonOrm\Test\Dto\Collection\ItemCollection;
use Matraux\JsonOrm\Test\Dto\Enum\CommonResult;

final class CommonEntity extends Entity
{
	/**
	 * @index NAME
	 */
	public ?string $name = null;

	/**
	 * @index TIME
	 * @codec Matraux\JsonOrm\Test\Dto\Codec\DateTimeCodec
	 */
	public ?DateTime $timestamp;

	/**
	 * @index STATUS
	 */
	public ?StatusEntity $status = null;

	/**
	 * @index ITEMS
	 */
	public ItemCollection $items;

	/**
	 * @index RESULT
	 */
	public string $result;
}
