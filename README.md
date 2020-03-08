# typed-properties-converter
before
```php
<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\Tests\Fixtures;

use TypedPropertiesConverter\Tests\Fixtures\Elements\SomeProperty;

class SomeClass
{
    /**
     * @var SomeProperty
     */
    private $property1;
    /**
     * @var SomeProperty|null
     */
    private $property2;
    /**
     * @var \Fully\Qualified\Class\Name
     */
    private $property3;
    /**
     * @var Relative\Class\Name
     */
    private $property4;
}
```

after
```php
<?php
declare(strict_types=1);

namespace Some\Name\Space;

use Some\Name\Space\SomeProperty;

class SomeClass
{
    private SomeProperty $property1;
    private ?SomeProperty $property1;
    private \Fully\Qualified\Class\Name $property3;
    private Relative\Class\Name $property4;
}
```

## Usage
```shell script
vendor/bin path/to/convert/dir/
```
