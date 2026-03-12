<?php declare(strict_types=1);

namespace Matraux\JsonOrm\Test\Dto\Enum;

enum CommonResult: string
{
	case Ok = 'success';
	case Fail = 'failed';
}
