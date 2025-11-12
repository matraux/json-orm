<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Dto\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORMTest\Dto\Entity\ImageEntity;

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
