<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Tester\Assert;
use Tester\TestCase;
use Matraux\JsonORMTest\Bootstrap;
use Matraux\JsonORMTest\FileSystem\Folder;
use Matraux\JsonORMTest\Support\UnitTester;
use Matraux\JsonORM\Json\SimpleJsonExplorer;
use Matraux\JsonORMTest\Dto\Entity\CommonEntity;
use Matraux\JsonORMTest\Dto\Entity\StatusEntity;
use Matraux\JsonORMTest\Dto\Entity\Enum\CommonResult;
use Matraux\JsonORMTest\Dto\Collection\CommonCollection;

final class EntityCest
{

	public function testReadEntity(UnitTester $tester): void
	{
		$commonCollection = self::createCommonCollection();
		$commonEntity = $commonCollection[1];
		$tester->assertEquals('Second', $commonEntity->name);
		$tester->assertEquals('offline', $commonEntity->status?->value);
		$tester->assertEquals(CommonResult::Fail, $commonEntity->result);
		$tester->assertEquals('First of items', $commonEntity->items[0]->name);
		$tester->assertEquals('Second of items', $commonEntity->items[1]->name);
		$tester->assertEquals('First icon', $commonEntity->items[1]->images[0]->icon);
		$tester->assertEquals('Second icon', $commonEntity->items[1]->images[1]->icon);
	}

	public function testJsonserializeEntity(UnitTester $tester): void
	{
		$commonEntity = CommonEntity::create();
		$commonEntity->name = 'First';
		$statusEntity = $commonEntity->status = StatusEntity::create();
		$statusEntity->value = 'online';

		$tester->assertEquals('{"NAME":"First","STATUS":{"VALUE":"online"}}', json_encode($commonEntity));
	}

	public function testStringableEntity(UnitTester $tester): void
	{
		$commonEntity = CommonEntity::create();
		$commonEntity->name = 'First';
		$statusEntity = $commonEntity->status = StatusEntity::create();
		$statusEntity->value = 'online';

		$tester->assertEquals('{"NAME":"First","STATUS":{"VALUE":"online"}}', (string) $commonEntity);
	}

	private static function createCommonCollection(): CommonCollection
	{
		$explorer = SimpleJsonExplorer::fromFile(Folder::create()->data->absolute . 'general.json');

		return CommonCollection::create($explorer);
	}

}
