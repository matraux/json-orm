<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Metadata;

use Matraux\JsonOrm\Entity\Entity;
use ReflectionClass;
use ReflectionException;

final class PropertyMetadataFactory
{

	/** @var array<class-string<Entity>,array<PropertyMetadata>> */
	protected static array $cache = [];

	/**
	 * @param class-string<Entity> $entityClass
	 * @return array<PropertyMetadata>
	 * @throws ReflectionException
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
