<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\Tests\Fixtures;

class NullableVar
{
    /**
     * @var Elements\SomeProperty|null
     */
    private $property1;

    /**
     * @var \TypedPropertiesConverter\Tests\Fixtures\Elements\SomeProperty|null
     */
    private $property2;
}
