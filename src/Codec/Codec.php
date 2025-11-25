<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Codec;

use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\PropertyMetadata;

interface Codec
{

	public function encode(mixed $value, PropertyMetadata $property): mixed;

	public function decode(Explorer $explorer, PropertyMetadata $property): mixed;

}
