<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Dto\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORMTest\Dto\Entity\CommonEntity;

/**
 * @extends Collection<CommonEntity>
 */
final class CommonCollection extends Collection
{

	protected static function getEntityClass(): string
	{
		return CommonEntity::class;
	}

}
