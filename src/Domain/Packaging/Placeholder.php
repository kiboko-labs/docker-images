<?php declare(strict_types=1);

namespace Kiboko\Cloud\Domain\Packaging;

final class Placeholder implements \Stringable
{
    private string $pattern;
    /** @var array<string,string> */
    private array $variables;

    public function __construct(string $pattern, array $variables = [])
    {
        $this->pattern = $pattern;
        $this->variables = $variables;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function reset(array $variables): Placeholder
    {
        return new self($this->pattern, $variables);
    }

    public function merge(array $variables): Placeholder
    {
        return new self($this->pattern, $variables + $this->variables);
    }

    public function __toString()
    {
        return strtr($this->pattern, $this->variables);
    }
}
