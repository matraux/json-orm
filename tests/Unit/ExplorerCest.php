<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Test\Collection;

use Codeception\Configuration;
use Matraux\FileSystem\File\File;
use Matraux\JsonOrm\Exception\ReadonlyAccessException;
use Matraux\JsonOrm\Json\SimpleExplorer;
use Matraux\JsonOrm\Test\Support\UnitTester;

final class ExplorerCest
{

	public function testExplorerFromFile(UnitTester $tester): void
	{
		$explorer = self::createSimpleJsonExplorer();
		$content = File::fromPath(Configuration::dataDir() . 'general.json')->content;

		$tester->assertEquals($content, (string) $explorer);
	}

	public function testExplorerFromString(UnitTester $tester): void
	{
		$content = File::fromPath(Configuration::dataDir() . 'general.json')->content;
		$explorer = SimpleExplorer::fromString($content);

		$tester->assertEquals($content, (string) $explorer);
	}

	public function testExplorerArrayAccess(UnitTester $tester): void
	{
		$explorer = self::createSimpleJsonExplorer();
		$tester->assertEquals('First', $explorer->withIndex(0)['NAME']);
		$tester->assertEquals('Second', $explorer->withIndex(1)['NAME']);
		$tester->assertEquals('Third', $explorer->withIndex(2)['NAME']);

		$tester->expectThrowable(ReadonlyAccessException::class, function () use ($explorer): void {
			$explorer['test'] = 'test';
		});

		$tester->expectThrowable(ReadonlyAccessException::class, function () use ($explorer): void {
			unset($explorer[0]);
		});
	}

	public function testExplorerIterator(UnitTester $tester): void
	{
		$explorer = self::createSimpleJsonExplorer();

		foreach ($explorer as $data) {
			$tester->assertIsArray($data);
		}
	}

	public function testExplorerCount(UnitTester $tester): void
	{
		$explorer = self::createSimpleJsonExplorer();
		$tester->assertCount(3, $explorer);
	}

	protected static function createSimpleJsonExplorer(): SimpleExplorer
	{
		return SimpleExplorer::fromFile(Configuration::dataDir() . 'general.json');
	}

}
