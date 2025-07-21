<?php declare(strict_types = 1);

namespace Matraux\JsonORMTest\Entity\Enum;

enum GeneralResult: string
{

	case Ok = 'success';
	case Fail = 'failed';

}
