<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity\Enum;

enum CommonResult: string
{

	case Ok = 'success';
	case Fail = 'failed';

}
