<?php

declare (strict_types=1);
namespace TypedPropertiesConverter\Tests\Fixtures;

class ScalarVar
{
    private int $property1;
    private string $property2;
    private bool $property3;
    /**
     * @var array
     */
    private array $property4;
    /**
     * @var int[]
     */
    private array $property5;
    /**
     * @var string[]
     */
    private array $property6;
    /**
     * @var bool[]
     */
    private array $property7;
    private ?int $property8;
    private ?string $property9;
    private ?bool $property10;
}