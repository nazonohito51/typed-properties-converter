<?php
declare(strict_types=1);

namespace TypedPropertiesConverter;

use phpDocumentor\Reflection\DocBlockFactory;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use TypedPropertiesConverter\NodeVisitor\TypedPropertiesModifier;
use TypedPropertiesConverter\NodeVisitor\TypeMapper;

class Converter
{
    /**
     * @var Parser
     */
    private $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    public function convert(string $phpFilePath): string
    {
        $modifier = new TypedPropertiesModifier(DocBlockFactory::createInstance(), new TypeMapper());
        $traverser = new NodeTraverser;
        $traverser->addVisitor($modifier);

        $originalContent = file_get_contents($phpFilePath);

        $ast = $this->parser->parse($originalContent);
        $modifiedStmts = $traverser->traverse($ast);

        if ($modifier->getNameContext() === 0) {
            return $originalContent;
        }

        $prettyPrinter = new Standard;
        return $prettyPrinter->prettyPrintFile($modifiedStmts);
    }
}
