<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Tester\Assert;
use Tester\TestCase;
use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Bootstrap;
use Matraux\JsonORMTest\Entity\StatusEntity;
use Matraux\JsonORMTest\Entity\GeneralEntity;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class CollectionTest extends TestCase
{

	public function testIterableCollection(): void
	{
		$generalCollection = self::createGeneralCollection();
		foreach ($generalCollection as $generalEntity) {
			Assert::type(GeneralEntity::class, $generalEntity);
		}

		foreach ($generalCollection as $generalEntity) {
			Assert::type(GeneralEntity::class, $generalEntity);
		}
	}

	public function testArrayGetCollection(): void
	{
		$generalCollection = self::createGeneralCollection();
		Assert::type(GeneralEntity::class, $generalCollection[0]);
		Assert::type(GeneralEntity::class, $generalCollection[1]);
		Assert::type(GeneralEntity::class, $generalCollection[2]);
	}

	public function testCountableCollection(): void
	{
		$generalCollection = self::createGeneralCollection();
		Assert::count(3, $generalCollection);
	}

	public function testArraySetCollection(): void
	{
		Assert::noError(function (): void {
			$generalCollection = GeneralCollection::create();
			$generalCollection[1] = GeneralEntity::create();
			$generalCollection[2] = GeneralEntity::create();
			$generalCollection[3] = GeneralEntity::create();
		});
	}

	public function testArrayUnsetCollection(): void
	{
		Assert::noError(function (): void {
			$generalCollection = GeneralCollection::create();
			$generalCollection[1] = GeneralEntity::create();
			unset($generalCollection[1]);
		});
	}

	public function testJsonserializeColection(): void
	{
		$generalCollection = GeneralCollection::create();

		$generalEntity = $generalCollection->createEntity();
		$generalEntity->name = 'First';
		$status = $generalEntity->status = StatusEntity::create();
		$status->value = 'online';

		$generalEntity = $generalCollection->createEntity();
		$generalEntity->name = 'Second';
		$status = $generalEntity->status = StatusEntity::create();
		$status->value = 'offline';

		Assert::equal('[{"NAME":"First","STATUS":{"VALUE":"online"}},{"NAME":"Second","STATUS":{"VALUE":"offline"}}]', json_encode($generalCollection));
	}

	public function testStringableCollection(): void
	{
		$generalCollection = GeneralCollection::create();

		$generalEntity = $generalCollection->createEntity();
		$generalEntity->name = 'First';
		$status = $generalEntity->status = StatusEntity::create();
		$status->value = 'online';

		$generalEntity = $generalCollection->createEntity();
		$generalEntity->name = 'Second';
		$status = $generalEntity->status = StatusEntity::create();
		$status->value = 'offline';

		Assert::equal('[{"NAME":"First","STATUS":{"VALUE":"online"}},{"NAME":"Second","STATUS":{"VALUE":"offline"}}]', (string) $generalCollection);
	}

	private static function createGeneralCollection(): GeneralCollection
	{
		$reader = Reader::fromFile(Bootstrap::Assets . 'general.json');

		return GeneralCollection::create($reader);
	}

}

(new CollectionTest())->run();
