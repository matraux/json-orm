<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Dto\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\JsonProperty;

final class ImageEntity extends Entity
{

	#[JsonProperty('ICON')]
	public ?string $icon = null;

	#[JsonProperty('IMAGE')]
	public ?string $image = null;

}
