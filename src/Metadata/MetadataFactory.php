<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Metadata;

use Matraux\JsonOrm\Entity\Entity;
use ReflectionClass;
use ReflectionException;

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
		if (array_key_exists($entityClass, self::$cache)) {
			return self::$cache[$entityClass];
		}

		$properties = new ReflectionClass($entityClass)->getProperties();
		$items = [];
		foreach ($properties as $property) {
			$items[] = new ReflectionClass(Metadata::class)->newLazyGhost(function (Metadata $propertyMetadata) use ($property): void {
				$propertyMetadata->__construct($property);
			});
		}

		return self::$cache[$entityClass] = $items;
	}
}
