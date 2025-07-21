<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Collection;

use Matraux\JsonORM\Json\Reader;
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

	public function testDataFromFile(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$data = Reader::fromFile(Bootstrap::Assets . 'general.json');

		Assert::matchFile(Bootstrap::Assets . 'general.json', (string) json_encode($data->data));
	}

	public function testDataCreate(): void
	{
		Bootstrap::purgeTemp(__FUNCTION__);

		$data = Reader::fromJson(FileSystem::read(Bootstrap::Assets . 'general.json'));

		Assert::matchFile(Bootstrap::Assets . 'general.json', (string) json_encode($data->data));
	}

	public function testDataWithIndex(): void
	{
		$data = Reader::fromFile(Bootstrap::Assets . 'general.json');
		Assert::equal('First', $data->withKey(0)->data['NAME']);
		Assert::equal('Second', $data->withKey(1)->data['NAME']);
		Assert::equal('Third', $data->withKey(2)->data['NAME']);
	}

}

(new ReaderTest())->run();
