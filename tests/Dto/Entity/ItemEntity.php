<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;
use Matraux\JsonOrmTest\Dto\Collection\ImageCollection;

final class ItemEntity extends Entity
{

	#[Property('NAME')]
	public ?string $name = null;

	#[Property('IMAGES')]
	public ImageCollection $images;

}
