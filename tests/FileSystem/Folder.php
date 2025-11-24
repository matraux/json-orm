<?php declare(strict_types = 1);

namespace Matraux\JsonOrmTest\FileSystem;

use Matraux\FileSystem\Folder\Folder as FileSystemFolder;

final class Folder extends FileSystemFolder
{

	public self $data
	{
		get => self::create()->addPath('tests')->addPath('data');
	}

}
