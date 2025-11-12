<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Dto\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\JsonProperty;
use Matraux\JsonORMTest\Dto\Collection\ImageCollection;

final class ItemEntity extends Entity
{

	#[JsonProperty('NAME')]
	public ?string $name = null;

	#[JsonProperty('IMAGES')]
	public ImageCollection $images;

}
