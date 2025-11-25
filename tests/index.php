<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest;

use Matraux\JsonOrm\Json\SimpleExplorer;
use Matraux\JsonOrmTest\Dto\Collection\CommonCollection;
use Matraux\JsonOrmTest\Utils\Tracy;
use Tracy\Debugger;

require_once __DIR__ . '/../vendor/autoload.php';

Tracy::setup();
Debugger::dump('Start dump');

foreach (CommonCollection::fromExplorer(SimpleExplorer::fromFile(__DIR__ . '/data/general.json')) as $commonEntity) {
	bdump($commonEntity);
	break;
}

Debugger::dump('Finish dump');
exit;
