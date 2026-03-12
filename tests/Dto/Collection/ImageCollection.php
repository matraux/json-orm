<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Collection;

use Matraux\JsonOrm\Collection\Collection;
use Matraux\JsonOrm\Test\Dto\Entity\ImageEntity;

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
