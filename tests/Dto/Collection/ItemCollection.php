<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Test\Dto\Collection;

use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Test\Dto\Entity\ItemEntity;

/**
 * @extends Collection<ItemEntity>
 */
final class ItemCollection extends Collection
{

	protected static function getEntityClass(): string
	{
		return ItemEntity::class;
	}

}
