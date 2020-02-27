<?php declare(strict_types=1);

namespace Builder\Assert;

use Builder\Package\RepositoryInterface;

final class XdebugVersion implements ConstraintInterface
{
    private RepositoryInterface $repository;
    private string $constraint;

    public function __construct(RepositoryInterface $repository, string $constraint)
    {
        $this->repository = $repository;
        $this->constraint = $constraint;
    }

    public function apply(\Traversable $tagRepository): \Traversable
    {
        foreach ($tagRepository as $tag) {
            if (!preg_match('/-xdebug(-|$)/', (string)$tag)) {
                continue;
            }

            yield new XdebugVersionConstraint($this->repository, $tag, $this->constraint);
        }
    }
}