<?php declare(strict_types=1);

namespace Builder\Assert;

use Builder\Package\RepositoryInterface;
use Builder\TagInterface;
use Composer\Semver\Semver;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

final class ICUVersionConstraint implements AssertionInterface
{
    private RepositoryInterface $repository;
    private TagInterface $tag;
    private string $constraint;

    public function __construct(RepositoryInterface $repository, TagInterface $tag, string $constraint)
    {
        $this->repository = $repository;
        $this->tag = $tag;
        $this->constraint = $constraint;
    }

    public function __invoke(): Result\AssertionResultInterface
    {
        $program =<<<"EOF"
try {
    \$reflector = new \ReflectionExtension('intl');
    ob_start();
    \$reflector->info();
    \$output = strip_tags(ob_get_clean());
    preg_match('/^ICU version (?:=>)?(.*)$/m', \$output, \$matches);
    echo trim(\$matches[1]);
} finally {
}
EOF;

        $process = new Process([
            'docker', 'run', '--rm', '-i', sprintf('%s:%s', (string)$this->repository, (string)$this->tag),
            'php', '-r', $program,
        ]);

        $version = null;
        try {
            $process->run(function ($type, $buffer) use ($process, &$version) {
                if (Process::ERR === $type) {
                    throw new ProcessFailedException($process);
                }

                if (preg_match('/^(\d+\.\d+)/i', $buffer, $matches)) {
                    $version = $matches[1];
                }
            });
        } catch (ProcessFailedException $exception) {
            return new Result\ICUMissingOrBroken($this->tag);
        }

        if (!is_string($version)) {
            return new Result\ICUMissingOrBroken($this->tag);
        }

        if (Semver::satisfies($version, $this->constraint)) {
            return new Result\ICUVersionMatches($this->tag, $version, $this->constraint);
        }

        return new Result\ICUVersionInvalid($this->tag, $version, $this->constraint);
    }
}