<?php declare(strict_types=1);

namespace Kiboko\Cloud\Platform\Context\ComposerPackageGuesser;

use Kiboko\Cloud\Domain\Packaging\RepositoryInterface;
use Kiboko\Cloud\Domain\Stack;

interface ComposerPackageDelegatedGuesserInterface
{
    public function matches(array $package): bool;
    public function guess(RepositoryInterface $repository, array $package): Stack\DTO\Context;
}
