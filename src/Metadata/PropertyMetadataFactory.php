<?php declare(strict_types = 1);

namespace Matraux\JsonORM\Metadata;

use Matraux\JsonORM\Entity\Entity;
use ReflectionClass;

final class PropertyMetadataFactory
{

	/** @var array<class-string<Entity>,array<PropertyMetadata>> */
	protected static array $cache;

	/**
	 * @param class-string<Entity> $entityClass
	 * @return array<PropertyMetadata>
	 */
	public static function create(string $entityClass): array
	{
		if ($items = self::$cache[$entityClass] ?? null) {
			return $items;
		}

		$properties = new ReflectionClass($entityClass)->getProperties();
		$items = [];
		foreach ($properties as $property) {
			$items[] = PropertyMetadata::create($property);
		}

		return self::$cache[$entityClass] = $items;
	}

}
