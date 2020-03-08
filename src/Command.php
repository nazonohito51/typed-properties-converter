<?php
declare(strict_types=1);

namespace TypedPropertiesConverter;

class Command
{
    /**
     * @var Converter
     */
    private $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function execute(string $path): void
    {
        $finder = new PhpFileFinder($path);

        foreach ($finder->getPhpFiles() as $phpFile) {
            $newCode = $this->converter->convert($phpFile);

            file_put_contents($phpFile, $newCode);
        }
    }
}
