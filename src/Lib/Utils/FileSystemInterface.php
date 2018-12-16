<?php

namespace app\Lib\Util;

interface FileSystemInterface
{
    public function getAppRoot(): string;

    public function getDbDir(): string;

    public function readFile(string $fullPath): string;

    public function writeFile(string $fullPath, string $content): void;
}
