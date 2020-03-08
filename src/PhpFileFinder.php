<?php
declare(strict_types=1);

namespace TypedPropertiesConverter;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class PhpFileFinder
{
    /**
     * @var string
     */
    private $path;

    public function __construct(string $path)
    {
        if (!is_file($path) && !is_dir($path)) {
            throw new \InvalidArgumentException($path . ' is not file or directory.');
        }

        $this->path = $path;
    }

    /**
     * @return string[]
     */
    public function getPhpFiles(): array
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->path));

        $phpFiles = [];
        foreach ($iterator as $fileInfo) {
            /** @var $fileInfo \SplFileInfo */
            if ($fileInfo->getExtension() === 'php') {
//                if (!$this->isIgnoreFile($fileInfo->getRealPath())) {
                $phpFiles[] = $fileInfo->getRealPath();
//                }
            }
        }

        return $phpFiles;
    }
}