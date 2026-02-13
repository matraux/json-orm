<?php declare(strict_types = 1);

namespace Matraux\JsonOrm\Test\Dto\Entity\Enum;

enum CommonResult: string
{

	case Ok = 'success';
	case Fail = 'failed';

}
