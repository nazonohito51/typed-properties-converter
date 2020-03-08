<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\Tests;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function getFixturePath(string $fileName): string
    {
        return __DIR__ . '/Fixtures/' . $fileName;
    }
}
