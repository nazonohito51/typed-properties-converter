<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\Tests\Fixtures;

use TypedPropertiesConverter\Tests\Fixtures\Elements\SomeProperty as SomeAlias;

class RelativeVarWithAlias
{
    /**
     * @var SomeAlias
     */
    private $property1;

    /**
     * @var SomeAlias[]
     */
    private $property2;
}
