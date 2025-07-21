<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Json;

use Attribute;

#[Attribute]
final class Property
{

	public function __construct(public readonly string $name)
	{
	}

}
