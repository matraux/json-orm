<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Metadata;

use Matraux\JsonOrm\Entity\Entity;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

final class MetadataFactory
{
	/** @var array<class-string<Entity>,array<Metadata>> */
	protected static array $cache = [];

	protected function __construct() {}

	/**
	 * @param class-string<Entity> $entityClass
	 * @return array<Metadata>
	 * @throws ReflectionException
	 */
	public static function create(string $entityClass): array
	{
		if (isset(self::$cache[$entityClass])) {
			return self::$cache[$entityClass];
		}

		$properties = (new ReflectionClass($entityClass))->getProperties(ReflectionProperty::IS_PUBLIC);
		$items = [];
		foreach ($properties as $property) {
			if ($property->isStatic()) {
				continue;
			}

			$items[] = new Metadata($property);
		}

		return self::$cache[$entityClass] = $items;
	}
}
