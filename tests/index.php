<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest;

use Matraux\JsonORM\Json\Reader;
use Matraux\JsonORMTest\Collection\GeneralCollection;

require_once __DIR__ . '/Bootstrap.php';

Bootstrap::dumper();
dump('Test dumper');

$reader = Reader::fromFile(Bootstrap::Assets . 'general.json');
$generalCollection = GeneralCollection::create($reader);
foreach ($generalCollection as $generalEntity) {
	bdump($generalEntity);
	bdump(isset($generalEntity->items) ? iterator_to_array($generalEntity->items) : 'žádné itemy'  );
}

dump('Exit dumper');
exit;
