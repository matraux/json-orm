<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORMTest\Entity\ItemEntity;

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
