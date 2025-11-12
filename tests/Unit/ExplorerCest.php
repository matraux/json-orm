<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\FileSystem\File\File;
use Nette\Utils\FileSystem;
use Matraux\JsonORMTest\Bootstrap;
use Matraux\JsonORMTest\Support\UnitTester;
use Matraux\JsonORM\Json\SimpleJsonExplorer;
use Matraux\JsonORM\Exception\ReadonlyAccessException;
use Matraux\JsonORMTest\FileSystem\Folder;

final class ExplorerCest
{

	public function testExplorerFromFile(UnitTester $tester): void
	{
		$explorer = static::createSimpleJsonExplorer();
		$content = File::fromPath(Folder::create()->data->absolute . 'general.json')->content;

		$tester->assertEquals($content, (string) $explorer);
	}

	public function testExplorerFromString(UnitTester $tester): void
	{
		$content = File::fromPath(Folder::create()->data->absolute . 'general.json')->content;
		$explorer = SimpleJsonExplorer::fromString($content);

		$tester->assertEquals($content, (string) $explorer);
	}

	public function testExplorerArrayAccess(UnitTester $tester): void
	{
		$explorer = static::createSimpleJsonExplorer();
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
		$explorer = static::createSimpleJsonExplorer();

		foreach ($explorer as $data) {
			$tester->assertIsArray($data);
		}
	}

	public function testExplorerCount(UnitTester $tester): void
	{
		$explorer = static::createSimpleJsonExplorer();
		$tester->assertCount(3, $explorer);
	}

	protected static function createSimpleJsonExplorer(): SimpleJsonExplorer
	{
		return SimpleJsonExplorer::fromFile(Folder::create()->data->absolute . 'general.json');
	}

}