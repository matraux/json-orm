<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Json;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class Property
{
	public function __construct(public string $name) {}

}
