<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\JsonProperty;

final class ImageEntity extends Entity
{

	#[JsonProperty('ICON')]
	public ?string $icon = null;

	#[JsonProperty('IMAGE')]
	public ?string $image = null;

}
