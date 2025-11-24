<?php declare(strict_types = 1);

$data = [
	[
		'NAME' => 'First',
		'TIME' => '21.11.2025 15:30:45.987654',
		'STATUS' => [
			'VALUE' => 'online',
		],
		'RESULT' => 'success',
	],
	[
		'NAME' => 'Second',
		'STATUS' => [
			'VALUE' => 'offline',
		],
		'RESULT' => 'failed',
		'ITEMS' => [
			[
				'NAME' => 'First of items',
			],
			[
				'NAME' => 'Second of items',
				'IMAGES' => [
					[
						'ICON' => 'First icon',
						'IMAGE' => 'First image',
					],
					[
						'ICON' => 'Second icon',
						'IMAGE' => 'Second image',
					],
				],
			],
			[
				'NAME' => 'Third of items',
			],
		],
	],
	[
		'NAME' => 'Third',
		'TIME' => '21.11.2025 20:00:00.000000',
		'STATUS' => [
			'VALUE' => 'suspend',
		],
	],
];

$data = json_encode($data);
file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'general.json', $data);

header('Content-type: application/json');
exit($data);
