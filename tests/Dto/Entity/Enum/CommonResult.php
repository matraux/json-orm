<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Dto\Entity\Enum;

enum CommonResult: string
{

	case Ok = 'success';
	case Fail = 'failed';

}
