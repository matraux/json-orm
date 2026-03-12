<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Test\Dto\Collection\ImageCollection;

final class ItemEntity extends Entity
{
	/**
	 * @index NAME
	 */
	public ?string $name = null;

	/**
	 * @index IMAGES
	 */
	public ImageCollection $images;
}
