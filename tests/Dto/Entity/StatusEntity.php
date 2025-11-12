<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Dto\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\JsonProperty;

final class StatusEntity extends Entity
{

	#[JsonProperty('VALUE')]
	public ?string $value = null;

}
