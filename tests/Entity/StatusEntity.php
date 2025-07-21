<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Matraux\JsonORM\Entity\Entity;
use Matraux\JsonORM\Json\Property;

final class StatusEntity extends Entity
{

	#[Property('VALUE')]
	public ?string $value = null;

}
