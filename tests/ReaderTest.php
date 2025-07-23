<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Exception\ReadonlyAccessException;
use Matraux\JsonORM\Json\JsonReader;
use Matraux\JsonORMTest\Bootstrap;
use Nette\Utils\FileSystem;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::tester();

/**
 * @testCase
 */
final class ReaderTest extends TestCase
{

	public function testReaderFromFile(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = JsonReader::fromFile(Bootstrap::Assets . 'general.json');

		Assert::matchFile(Bootstrap::Assets . 'general.json', (string) $reader);
	}

	public function testReaderFromString(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = JsonReader::fromString(FileSystem::read(Bootstrap::Assets . 'general.json'));

		Assert::matchFile(Bootstrap::Assets . 'general.json', (string) $reader);
	}

	public function testReaderArrayAccess(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = JsonReader::fromFile(Bootstrap::Assets . 'general.json');

		Assert::equal('First', $reader->withKey(0)['NAME']);
		Assert::equal('Second', $reader->withKey(1)['NAME']);
		Assert::equal('Third', $reader->withKey(2)['NAME']);

		Assert::exception(function () use ($reader): void {
			$reader['test'] = 'test';
		}, ReadonlyAccessException::class);

		Assert::exception(function () use ($reader): void {
			unset($reader[0]);
		}, ReadonlyAccessException::class);
	}

	public function testReaderIterator(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = JsonReader::fromFile(Bootstrap::Assets . 'general.json');

		foreach ($reader as $data) {
			Assert::type('array', $data);
		}
	}

	public function testReaderCount(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$reader = JsonReader::fromFile(Bootstrap::Assets . 'general.json');

		Assert::equal(count($reader), 3);
	}

}

(new ReaderTest())->run();
