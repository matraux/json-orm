<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Json;

use Attribute;

#[Attribute]
final class JsonProperty
{

	public function __construct(public readonly string $name)
	{
	}

}
