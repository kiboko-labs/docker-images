<?php declare(strict_types=1);

namespace test\Kiboko\Cloud\Fixture\Platform;

use test\Kiboko\Cloud\Fixture\FixtureInterface;
use test\Kiboko\Cloud\Fixture\Placeholder\ContextReplacement;
use test\Kiboko\Cloud\Fixture\Placeholder\Regex;
use test\Kiboko\Cloud\Fixture\VisitableFixture;
use test\Kiboko\Cloud\WizardAssertionFixtureProvider;

final class MiddlewareCommunityFixture implements FixtureInterface
{
    use VisitableFixture;

    private string $dbms;

    public function __construct(string $dbms)
    {
        $this->dbms = $dbms;
    }

    public function get(): iterable
    {
        yield (new WizardAssertionFixtureProvider(['7.4'], 'middleware', ['1.0'], false, $this->dbms))
            ->expectWizardMessages(
                'Choosing Middleware Community Edition, version %applicationVersion%.',
            )
            ->expectImageBuildProcesses(
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-fpm'), '-'],
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-cli'), '-'],
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-fpm-%application%-%applicationEdition%-%applicationVersion%-%dbms%'), '--build-arg', new Regex('/^SOURCE_VARIATION=/'), '-'],
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-cli-%application%-%applicationEdition%-%applicationVersion%-%dbms%'), '--build-arg', new Regex('/^SOURCE_VARIATION=/'), '-'],
            )
        ;
        yield (new WizardAssertionFixtureProvider(['8.0'], 'middleware', ['1.0'], false, $this->dbms))
            ->expectWizardMessages(
                'Choosing Middleware Community Edition, version %applicationVersion%.',
            )
            ->expectImageBuildProcesses(
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-fpm'), '-'],
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-cli'), '-'],
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-fpm-%application%-%applicationEdition%-%applicationVersion%-%dbms%'), '--build-arg', new Regex('/^SOURCE_VARIATION=/'), '-'],
                ['docker', 'build', '--rm', '--tag', new ContextReplacement('kiboko-test/php:%phpVersion%-cli-%application%-%applicationEdition%-%applicationVersion%-%dbms%'), '--build-arg', new Regex('/^SOURCE_VARIATION=/'), '-'],
            )
            ->withExperimental()
        ;
    }
}