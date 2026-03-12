<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;
use Matraux\JsonOrm\Test\Dto\Collection\ImageCollection;

final class ItemEntity extends Entity
{
	#[Property('NAME')]
	public ?string $name = null;

	#[Property('IMAGES')]
	public ImageCollection $images;
}
