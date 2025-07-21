<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\Property;

final class ImageEntity extends Entity
{

	#[Property('ICON')]
	public ?string $icon = null;

	#[Property('IMAGE')]
	public ?string $image = null;

}
