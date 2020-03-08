<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\Tests\Fixtures;

class ArrayObjectVar
{
    /**
     * @var \TypedPropertiesConverter\Tests\Fixtures\Elements\SomeProperty[]
     */
    private $property1;

    /**
     * @var Elements\SomeProperty[]
     */
    private $property2;
}
