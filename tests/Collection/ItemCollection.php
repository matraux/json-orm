<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Entity\ItemEntity;

/**
 * @extends Collection<ItemEntity>
 */
final class ItemCollection extends Collection
{

	protected function getEntity(?Reader $reader = null): ItemEntity
	{
		return ItemEntity::create($reader);
	}

}
