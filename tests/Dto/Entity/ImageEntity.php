<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Entity;

use Matraux\JsonOrm\Entity\Entity;
use Matraux\JsonOrm\Json\Property;

final class ImageEntity extends Entity
{
	/**
	 * @index ICON
	 */
	public ?string $icon = null;

	/**
	 * @index IMAGE
	 */
	public ?string $image = null;
}
