<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORMTest\Entity\ImageEntity;

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
