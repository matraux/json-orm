<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\JsonProperty;
use Matraux\JsonORMTest\Collection\ImageCollection;

final class ItemEntity extends Entity
{

	#[JsonProperty('NAME')]
	public ?string $name = null;

	#[JsonProperty('IMAGES')]
	public ImageCollection $images;

}
