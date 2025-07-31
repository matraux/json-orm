<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Exception\ReadonlyAccessException;
use Matraux\JsonORM\Json\SimpleJsonExplorer;
use Matraux\JsonORMTest\Bootstrap;
use Nette\Utils\FileSystem;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class ExplorerTest extends TestCase
{

	public function testExplorerFromFile(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = SimpleJsonExplorer::fromFile(Bootstrap::Assets . 'general.json');

		Assert::matchFile(Bootstrap::Assets . 'general.json', (string) $reader);
	}

	public function testExplorerFromString(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = SimpleJsonExplorer::fromString(FileSystem::read(Bootstrap::Assets . 'general.json'));

		Assert::matchFile(Bootstrap::Assets . 'general.json', (string) $reader);
	}

	public function testExplorerArrayAccess(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = SimpleJsonExplorer::fromFile(Bootstrap::Assets . 'general.json');

		Assert::equal('First', $reader->withIndex(0)['NAME']);
		Assert::equal('Second', $reader->withIndex(1)['NAME']);
		Assert::equal('Third', $reader->withIndex(2)['NAME']);

		Assert::exception(function () use ($reader): void {
			$reader['test'] = 'test';
		}, ReadonlyAccessException::class);

		Assert::exception(function () use ($reader): void {
			unset($reader[0]);
		}, ReadonlyAccessException::class);
	}

	public function testExplorerIterator(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = SimpleJsonExplorer::fromFile(Bootstrap::Assets . 'general.json');

		foreach ($reader as $data) {
			Assert::type('array', $data);
		}
	}

	public function testExplorerCount(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = SimpleJsonExplorer::fromFile(Bootstrap::Assets . 'general.json');

		Assert::equal(count($reader), 3);
	}

}

(new ExplorerTest())->run();
