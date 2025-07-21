<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Collection\Collection;
use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Entity\ImageEntity;

/**
 * @extends Collection<ImageEntity>
 */
final class ImageCollection extends Collection
{

	protected function getEntity(?Reader $reader = null): ImageEntity
	{
		return ImageEntity::create($reader);
	}

}
