<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Test\Dto\Collection;

use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Test\Dto\Entity\CommonEntity;

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
