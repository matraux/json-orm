<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity;

use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Bootstrap;
use Matraux\JsonORMTest\Collection\CommonCollection;
use Matraux\JsonORMTest\Entity\Enum\CommonResult;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class EntityTest extends TestCase
{

	public function testReadEntity(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$generalCollection = self::createGeneralCollection();
		$generalEntity = $generalCollection[1];
		Assert::equal('Second', $generalEntity->name);
		Assert::equal('offline', $generalEntity->status?->value);
		Assert::equal(CommonResult::Fail, $generalEntity->result);
		Assert::equal('First of items', $generalEntity->items[0]->name);
		Assert::equal('Second of items', $generalEntity->items[1]->name);
		Assert::equal('First icon', $generalEntity->items[1]->images[0]->icon);
		Assert::equal('Second icon', $generalEntity->items[1]->images[1]->icon);
	}

	public function testJsonserializeEntity(): void
	{
		$generalEntity = CommonEntity::create();
		$generalEntity->name = 'First';
		$statusEntity = $generalEntity->status = StatusEntity::create();
		$statusEntity->value = 'online';

		Assert::equal('{"NAME":"First","STATUS":{"VALUE":"online"}}', json_encode($generalEntity));
	}

	public function testStringableEntity(): void
	{
		$generalEntity = CommonEntity::create();
		$generalEntity->name = 'First';
		$statusEntity = $generalEntity->status = StatusEntity::create();
		$statusEntity->value = 'online';

		Assert::equal('{"NAME":"First","STATUS":{"VALUE":"online"}}', (string) $generalEntity);
	}

	private static function createGeneralCollection(): CommonCollection
	{
		$reader = Reader::fromFile(Bootstrap::Assets . 'general.json');

		return CommonCollection::create($reader);
	}

}

(new EntityTest())->run();
