<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\Tests\Unit;

use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\NodeTraverser;
use PhpParser\ParserFactory;
use TypedPropertiesConverter\Converter;
use TypedPropertiesConverter\NodeVisitor\TypedPropertiesModifier;
use TypedPropertiesConverter\NodeVisitor\TypeMapper;
use TypedPropertiesConverter\Tests\TestCase;

class ConverterTest extends TestCase
{
    /**
     * @param string $fixturePath
     * @param string $expectedPath
     * @testWith ["FqsenVar.php", "Expected/FqsenVar.php"]
     *           ["RelativeVar.php", "Expected/RelativeVar.php"]
     *           ["RelativeVarWithUse.php", "Expected/RelativeVarWithUse.php"]
     *           ["NullableVar.php", "Expected/NullableVar.php"]
     *           ["ScalarVar.php", "Expected/ScalarVar.php"]
     *           ["ArrayObjectVar.php", "Expected/ArrayObjectVar.php"]
     *           ["ArrayObjectVarWithUse.php", "Expected/ArrayObjectVarWithUse.php"]
     *           ["RelativeVarWithAlias.php", "Expected/RelativeVarWithAlias.php"]
     */
    public function testConvert(string $fixturePath, string $expectedPath)
    {
        $parser = (new ParserFactory)->create(ParserFactory::ONLY_PHP7);
        $sut = new Converter($parser);

        $actual = $sut->convert($this->getFixturePath($fixturePath));

        $this->assertSame(file_get_contents($this->getFixturePath($expectedPath)), $actual);
    }
}
