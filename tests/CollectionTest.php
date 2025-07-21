<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Bootstrap;
use Matraux\JsonORMTest\Entity\CommonEntity;
use Matraux\JsonORMTest\Entity\StatusEntity;
use Tester\Assert;
use Tester\TestCase;

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
			Assert::type(CommonEntity::class, $generalEntity);
		}

		foreach ($generalCollection as $generalEntity) {
			Assert::type(CommonEntity::class, $generalEntity);
		}
	}

	public function testArrayGetCollection(): void
	{
		$generalCollection = self::createGeneralCollection();
		Assert::type(CommonEntity::class, $generalCollection[0]);
		Assert::type(CommonEntity::class, $generalCollection[1]);
		Assert::type(CommonEntity::class, $generalCollection[2]);
	}

	public function testCountableCollection(): void
	{
		$generalCollection = self::createGeneralCollection();
		Assert::count(3, $generalCollection);
	}

	public function testArraySetCollection(): void
	{
		Assert::noError(function (): void {
			$generalCollection = CommonCollection::create();
			$generalCollection[1] = CommonEntity::create();
			$generalCollection[2] = CommonEntity::create();
			$generalCollection[3] = CommonEntity::create();
		});
	}

	public function testArrayUnsetCollection(): void
	{
		Assert::noError(function (): void {
			$generalCollection = CommonCollection::create();
			$generalCollection[1] = CommonEntity::create();
			unset($generalCollection[1]);
		});
	}

	public function testJsonserializeColection(): void
	{
		$generalCollection = CommonCollection::create();

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
		$generalCollection = CommonCollection::create();

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

	private static function createGeneralCollection(): CommonCollection
	{
		$reader = Reader::fromFile(Bootstrap::Assets . 'general.json');

		return CommonCollection::create($reader);
	}

}

(new CollectionTest())->run();
