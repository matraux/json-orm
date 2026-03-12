<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Json;

use Attribute;
use Exception;
use RuntimeException;

final class Property
{
	protected string $name;

	public function __construct(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function __get(string $name)
	{
		switch ($name) {
			case 'name':
				return $this->name;
			default:
				throw new RuntimeException(sprintf('Undefined property %s::$%s.', static::class, $name));
		}
	}
}
