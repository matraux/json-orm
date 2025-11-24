<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;

final class StatusEntity extends Entity
{

	#[Property('VALUE')]
	public ?string $value = null;

}
