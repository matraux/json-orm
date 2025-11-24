<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\Unit;

use Matraux\JsonOrm\Json\SimpleExplorer;
use Matraux\JsonOrmTest\Dto\Collection\CommonCollection;
use Matraux\JsonOrmTest\Dto\Entity\CommonEntity;
use Matraux\JsonOrmTest\Dto\Entity\StatusEntity;
use Matraux\JsonOrmTest\FileSystem\Folder;
use Matraux\JsonOrmTest\Support\UnitTester;

final class CollectionCest
{

	public function testCollectionIterable(UnitTester $tester): void
	{
		$commonCollection = self::createCommonCollection($tester);
		foreach ($commonCollection as $generalEntity) {
			$tester->assertInstanceOf(CommonEntity::class, $generalEntity);
		}
	}

	public function testCollectionArrayGet(UnitTester $tester): void
	{
		$commonCollection = self::createCommonCollection($tester);
		$tester->assertInstanceOf(CommonEntity::class, $commonCollection[0]);
		$tester->assertInstanceOf(CommonEntity::class, $commonCollection[1]);
		$tester->assertInstanceOf(CommonEntity::class, $commonCollection[2]);
	}

	public function testCollectionCountable(UnitTester $tester): void
	{
		$commonCollection = self::createCommonCollection($tester);
		$tester->assertCount(3, $commonCollection);
	}

	public function testCollectionArraySet(UnitTester $tester): void
	{
		$commonCollection = CommonCollection::create();
		$commonCollection[1] = CommonEntity::create();
		$commonCollection[2] = CommonEntity::create();
		$commonCollection[3] = CommonEntity::create();
	}

	public function testCollectionArrayUnset(UnitTester $tester): void
	{
		$commonCollection = CommonCollection::create();
		$commonCollection[1] = CommonEntity::create();
		unset($commonCollection[1]);
	}

	public function testJsonserializeColection(UnitTester $tester): void
	{
		$commonCollection = CommonCollection::create();

		$commonEntity = $commonCollection->createEntity();
		$commonEntity->name = 'First';
		$status = $commonEntity->status = StatusEntity::create();
		$status->value = 'online';

		$commonEntity = $commonCollection->createEntity();
		$commonEntity->name = 'Second';
		$status = $commonEntity->status = StatusEntity::create();
		$status->value = 'offline';

		$tester->assertEquals('[{"NAME":"First","STATUS":{"VALUE":"online"}},{"NAME":"Second","STATUS":{"VALUE":"offline"}}]', json_encode($commonCollection));
	}

	public function testCollectionStringable(UnitTester $tester): void
	{
		$commonCollection = CommonCollection::create();

		$generalEntity = $commonCollection->createEntity();
		$generalEntity->name = 'First';
		$status = $generalEntity->status = StatusEntity::create();
		$status->value = 'online';

		$generalEntity = $commonCollection->createEntity();
		$generalEntity->name = 'Second';
		$status = $generalEntity->status = StatusEntity::create();
		$status->value = 'offline';

		$tester->assertEquals('[{"NAME":"First","STATUS":{"VALUE":"online"}},{"NAME":"Second","STATUS":{"VALUE":"offline"}}]', (string) $commonCollection);
	}

	protected static function createCommonCollection(UnitTester $tester): CommonCollection
	{
		$explorer = SimpleExplorer::fromFile(Folder::create()->data->absolute . 'general.json');

		return CommonCollection::create($explorer);
	}

}
