<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\Property;
use Matraux\JsonORMTest\Collection\ImageCollection;

final class ItemEntity extends Entity
{

	#[Property('NAME')]
	public ?string $name = null;

	#[Property('IMAGES')]
	public ImageCollection $images;

}
