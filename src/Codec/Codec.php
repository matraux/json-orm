<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

interface Codec
{

	public function encode(mixed $value): mixed;

	public function decode(mixed $value): mixed;

}
