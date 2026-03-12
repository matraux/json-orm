<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Codec;

use Matraux\JsonOrm\Json\Explorer;
use Matraux\JsonOrm\Metadata\Metadata;

/**
 * Implementing classes must also declare:
 * #[\Attribute(\Attribute::TARGET_PROPERTY)]
 * Otherwise the codec cannot be used as a property attribute.
 */
interface Codec
{

	/**
	 * @param mixed $value
	 * @return mixed
	 */
	public function encode($value, Metadata $metadata);

	/**
	 * @return mixed
	 */
	public function decode(Explorer $explorer, Metadata $metadata);
}
