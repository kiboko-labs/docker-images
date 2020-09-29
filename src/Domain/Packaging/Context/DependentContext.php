<?php

declare(strict_types=1);

namespace Kiboko\Cloud\Domain\Packaging\Context;

use Kiboko\Cloud\Domain\Packaging\Placeholder;

final class DependentContext implements ContextInterface, \IteratorAggregate
{
    use ContextTrait;

    private ContextInterface $dependency;

    public function __construct(
        ?ContextInterface $parent,
        ContextInterface $dependency,
        array $variables = []
    ) {
        $this->parent = $parent;
        $this->localVariables = $variables;
        $this->dependency = $dependency;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getArrayCopy());
    }

    public function getBuildableParent(?string $path = null, array $variables = []): ?BuildableContextInterface
    {
        if (null === $this->parent) {
            return null;
        }

        return $this->parent->asBuildable($path, $variables);
    }

    public function asBuildable(?string $path = null, array $variables = []): BuildableContextInterface
    {
        return new DependentBuildableContext($this->parent, $this->dependency, $path !== null ? new Placeholder($path) : null, $variables + $this->getLocalArrayCopy());
    }

    public function hasParent(): bool
    {
        return null !== $this->parent;
    }

    public function getParent(): ContextInterface
    {
        return null !== $this->parent ? clone $this->parent : new Context(null);
    }

    public function dependencyOrParent(): ContextInterface
    {
        return clone $this->dependency;
    }
}
