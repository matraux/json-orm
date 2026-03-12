<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;

final class StatusEntity extends Entity
{
	/**
	 * @index VALUE
	 */
	public ?string $value = null;
}
