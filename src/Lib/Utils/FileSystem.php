<?php

namespace App\Lib\Utils;

use app\Lib\Util\FileSystemInterface;

class FileSystem implements FileSystemInterface
{
    /**
     * @var string
     */
    private $appRootDir;
    /**
     * @var string
     */
    private $dbDir;

    public function __construct(
        string $dbRelativeDir
    ) {
        $this->appRootDir = realpath(__DIR__ . '/../../../..');
        $this->dbDir = "$this->appRootDir/$dbRelativeDir";
    }

    public function getAppRoot(): string
    {
        return $this->appRootDir;
    }

    public function getDbDir(): string
    {
        return $this->dbDir;
    }

    public function readFile(string $fullPath): string
    {
        return file_get_contents($fullPath);
    }

    public function writeFile(string $fullPath, string $content): void
    {
        file_put_contents($fullPath, $content);
    }
}
