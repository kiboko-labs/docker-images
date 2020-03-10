<?php declare(strict_types=1);

namespace Builder\Domain\Packaging\Native;

use Builder\Domain\Packaging;
use Builder\Domain\Packaging\Tag\TagInterface;

final class TagVariationReference implements Packaging\Tag\TagReferenceInterface, Packaging\Tag\DependentTagInterface
{
    private Packaging\RepositoryInterface $repository;
    private Packaging\Placeholder $name;
    private Packaging\Context\BuildableContextInterface $context;

    public function __construct(
        Packaging\RepositoryInterface $repository,
        Packaging\Context\BuildableContextInterface $context
    ) {
        $this->repository = $repository;
        $this->name = new Packaging\Placeholder('%php.version%-%php.flavor%-%package.variation%', $context->getArrayCopy());
        $this->context = $context;
    }

    public function getContext(): Packaging\Context\ContextInterface
    {
        return $this->context;
    }

    public function __toString()
    {
        return (string) $this->name;
    }

    public function getRepository(): Packaging\RepositoryInterface
    {
        return $this->repository;
    }

    public function getParent(): TagInterface
    {
        return new TagReference($this->repository, $this->context);
    }
}