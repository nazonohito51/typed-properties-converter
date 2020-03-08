<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\NodeVisitor;

use phpDocumentor\Reflection\DocBlockFactory;
use phpDocumentor\Reflection\Types\Context;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitor\NameResolver;

class TypedPropertiesModifier extends NameResolver
{
    /**
     * @var DocBlockFactory
     */
    private $factory;
    /**
     * @var TypeMapper
     */
    private $typeMapper;
    /**
     * @var string[]
     */
    private $aliases = [];
    /**
     * @var int
     */
    private $modifiedCount = 0;

    public function __construct(DocBlockFactory $docBlockFactory, TypeMapper $typeMapper)
    {
        parent::__construct();
        $this->factory = $docBlockFactory;
        $this->typeMapper = $typeMapper;
    }

    public function enterNode(Node $node)
    {
        parent::enterNode($node);

        if ($node instanceof Use_) {
            foreach ($node->uses as $use) {
                $this->aliases[$use->getAlias()->toString()] = $use->name->toString();
            }
        }
    }

    public function leaveNode(Node $node)
    {
        parent::leaveNode($node);

        if ($node instanceof Node\Stmt\Property) {
            if (is_null($node->getDocComment())) {
                return null;
            }

            $docBlock = $this->factory->create(
                (string)$node->getDocComment(),
                new Context($this->nameContext->getNamespace()->toString(), $this->aliases)
            );
            if (empty($varTags = $docBlock->getTagsByName('var'))) {
                return null;
            } elseif (count($varTags) > 1) {
                return null;
            }

            /** @var \phpDocumentor\Reflection\DocBlock\Tags\Var_ $varTag */
            $varTag = $varTags[0];
            $typeInVarTag = $varTag->getType();
            $type = $this->typeMapper->convertToPhpParserType($typeInVarTag, $this->getNameContext());

            $removeDocComment = true;
            if ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\Array_ ||
                $typeInVarTag instanceof \phpDocumentor\Reflection\Types\Iterable_) {
                $removeDocComment = false;
            }

            if ($removeDocComment) {
                $attributes = $node->getAttributes();
                unset($attributes['comments']);
                $node->setAttribute('comments', []);
            }

            $node->type = $type;
            $this->modifiedCount++;
            return $node;
        }

        return null;
    }

    public function getModifiedCount(): int
    {
        return $this->modifiedCount;
    }
}
