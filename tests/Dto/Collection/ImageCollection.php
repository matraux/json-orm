<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Collection;

use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrmTest\Dto\Entity\ImageEntity;

/**
 * @extends Collection<ImageEntity>
 */
final class ImageCollection extends Collection
{

	protected static function getEntityClass(): string
	{
		return ImageEntity::class;
	}

}
