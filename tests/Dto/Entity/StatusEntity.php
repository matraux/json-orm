<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\JsonProperty;

final class StatusEntity extends Entity
{

	#[JsonProperty('VALUE')]
	public ?string $value = null;

}
