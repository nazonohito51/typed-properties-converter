<?php
declare(strict_types=1);

namespace TypedPropertiesConverter\NodeVisitor;

use phpDocumentor\Reflection\Type;
use phpDocumentor\Reflection\Types\Object_;
use PhpParser\NameContext;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\Stmt\Use_;

class TypeMapper
{
    public function convertToPhpParserType(Type $typeInVarTag, NameContext $nameContext)
    {
        if ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\Compound) {
            if (count($typeInVarTag->getIterator()) <= 0 || 2 < count($typeInVarTag->getIterator())) {
                return null;
            }

            if (count($typeInVarTag->getIterator()) === 1) {
                return $nameContext->getShortName((string)$typeInVarTag, Use_::TYPE_NORMAL);
            } elseif (count($typeInVarTag->getIterator()) === 2 && $typeInVarTag->contains(new \phpDocumentor\Reflection\Types\Null_())) {
                $otherType = $typeInVarTag->get(0) instanceof \phpDocumentor\Reflection\Types\Null_ ? $typeInVarTag->get(1) : $typeInVarTag->get(0);

                if ($otherType instanceof \phpDocumentor\Reflection\Types\Object_) {
                    return new NullableType(
                        $nameContext->getShortName($this->getNameFrom($otherType), Use_::TYPE_NORMAL)
                    );
                } else {
                    return new NullableType(new Identifier((string)$otherType));
                }
            } else {
                // 'unknown pattern'
                return null;
            }
        } elseif ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\Object_) {
            return $nameContext->getShortName(
                $this->getNameFrom($typeInVarTag),
                Use_::TYPE_NORMAL
            );
        } elseif ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\Boolean) {
            return new Identifier((string)$typeInVarTag);
        } elseif ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\String_) {
            return new Identifier((string)$typeInVarTag);
        } elseif ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\Integer) {
            return new Identifier((string)$typeInVarTag);
        } elseif ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\Array_) {
            $removeDocComment = false;
            return new Identifier('array');
        } elseif ($typeInVarTag instanceof \phpDocumentor\Reflection\Types\Iterable_) {
            $removeDocComment = false;
            return new Identifier('iterable');
        }

        return null;
    }

    private function maybeRelativePath(\phpDocumentor\Reflection\Types\Object_ $type, NameContext $nameContext)
    {
        // absolute path
        if (substr($type->getFqsen()->getName(), 0, 1) === '\\') {
            $fqsen = substr($type->getFqsen()->getName(), 1);
            return $nameContext->getShortName($fqsen, Use_::TYPE_NORMAL);
        }

        // relative path or alias
        $possibleNames = $nameContext->getPossibleNames($type->getFqsen()->getName(), Use_::TYPE_NORMAL);
        if (count($possibleNames) > 1) {
            return $nameContext->getShortName($type->getFqsen()->getName(), Use_::TYPE_NORMAL);
        }

        // relative path
        $name = Name::concat($nameContext->getNamespace(), $type->getFqsen()->getName());
        return $nameContext->getShortName($name->toString(), Use_::TYPE_NORMAL);
    }

    private function getNameFrom(Object_ $typeInVarTag)
    {
        $name = (string)$typeInVarTag;
        if (substr($name, 0, 1) === '\\') {
            $name = substr($name, 1);
        }

        return $name;
    }
}
