<?php

declare (strict_types=1);
namespace TypedPropertiesConverter\Tests\Fixtures;

use TypedPropertiesConverter\Tests\Fixtures\Elements\SomeProperty as SomeAlias;
class RelativeVarWithAlias
{
    private SomeAlias $property1;
    /**
     * @var SomeAlias[]
     */
    private array $property2;
}