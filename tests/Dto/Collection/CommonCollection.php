<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Collection;

use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrmTest\Dto\Entity\CommonEntity;

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
