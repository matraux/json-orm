<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Entity\CommonEntity;

/**
 * @extends Collection<CommonEntity>
 */
final class CommonCollection extends Collection
{

	protected function getEntity(?Reader $reader = null): CommonEntity
	{
		return CommonEntity::create($reader);
	}

}
