<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Entity\GeneralEntity;

/**
 * @extends Collection<GeneralEntity>
 */
final class GeneralCollection extends Collection
{

	protected function getEntity(?Reader $reader = null): GeneralEntity
	{
		return GeneralEntity::create($reader);
	}

}
