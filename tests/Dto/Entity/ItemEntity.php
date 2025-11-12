<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\JsonProperty;
use Matraux\JsonOrmTest\Dto\Collection\ImageCollection;

final class ItemEntity extends Entity
{

	#[JsonProperty('NAME')]
	public ?string $name = null;

	#[JsonProperty('IMAGES')]
	public ImageCollection $images;

}
