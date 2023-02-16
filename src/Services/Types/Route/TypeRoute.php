<?php

namespace Kiwilan\Typescriptable\Services\Types\Route;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;

class TypeRoute
{
    protected function __construct(
        protected string $uri,
        protected ?string $fullUri = null,
        protected ?string $name = null,
        protected ?string $nameCamel = null,
        protected array $methods = [],
        protected string $method = 'GET',
        protected array $parameters = [],
    ) {
    }

    public static function make(Route $route): self
    {
        $name = $route->getName();
        if (! $name) {
            $name = $route->uri();
        }

        $type = new self(
            uri: $route->uri(),
            fullUri: $route->uri() !== '/' ? "/{$route->uri()}" : '/',
            name: $name,
            nameCamel: Str::camel($name),
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

    public function nameCamel(): ?string
    {
        return $this->nameCamel;
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
