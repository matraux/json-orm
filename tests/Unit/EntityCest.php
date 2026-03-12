<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Entity;

use Codeception\Configuration;
use Matraux\JsonOrm\Json\SimpleExplorer;
use Matraux\JsonOrm\Test\Dto\Collection\CommonCollection;
use Matraux\JsonOrm\Test\Dto\Entity\CommonEntity;
use Matraux\JsonOrm\Test\Dto\Entity\StatusEntity;
use Matraux\JsonOrm\Test\Dto\Enum\CommonResult;
use Matraux\JsonOrm\Test\Support\UnitTester;

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
		$explorer = SimpleExplorer::fromFile(Configuration::dataDir() . 'general.json');

		return CommonCollection::fromExplorer($explorer);
	}
}
