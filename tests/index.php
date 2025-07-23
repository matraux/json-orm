<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest;

use Matraux\JsonORM\Json\JsonReader;
use Matraux\JsonORMTest\Collection\CommonCollection;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::dumper();
dump('Test dumper');

$reader = JsonReader::fromFile(Bootstrap::Assets . 'general.json');

foreach (CommonCollection::create($reader) as $entity) {
	echo($entity->name) . '<br>';

	foreach ($entity->items ?? [] as $item) {
		echo($item->name) . '<br>';
	}
}

dump('Exit dumper');
exit;
