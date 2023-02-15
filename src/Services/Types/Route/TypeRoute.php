<?php

namespace Kiwilan\Typescriptable\Services\Types\Route;

use Illuminate\Routing\Route;

class TypeRoute
{
    protected function __construct(
        protected string $uri,
        protected ?string $fullUri = null,
        protected ?string $name = null,
        protected array $methods = [],
        protected string $method = 'GET',
        protected array $parameters = [],
    ) {
    }

    public static function make(Route $route): self
    {
        $type = new self(
            uri: $route->uri(),
            fullUri: $route->uri() !== '/' ? "/{$route->uri()}" : '/',
            name: $route->getName(),
            methods: $route->methods(),
            parameters: $route->parameterNames(),
        );

        $type->method = $type->setMethod();

        return $type;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function fullUri(): string
    {
        return $this->fullUri;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function methods(): array
    {
        return $this->methods;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    private function setMethod(): string
    {
        $method = 'GET';

        foreach ($this->methods as $value) {
            if ($value !== 'HEAD') {
                $method = $value;
            }
        }

        return $method;
    }
}
