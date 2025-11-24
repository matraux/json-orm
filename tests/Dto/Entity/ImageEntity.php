<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;

final class ImageEntity extends Entity
{

	#[Property('ICON')]
	public ?string $icon = null;

	#[Property('IMAGE')]
	public ?string $image = null;

}
