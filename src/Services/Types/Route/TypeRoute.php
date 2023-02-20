<?php

namespace Kiwilan\Typescriptable\Services\Types\Route;

use Illuminate\Routing\Route;
use Kiwilan\Typescriptable\TypescriptableConfig;

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
        $name = TypescriptableConfig::routesUsePath()
            ? $route->uri()
            : $route->getName();

        if (! $name) {
            $name = $route->uri();
        }

        $type = new self(
            uri: $route->uri(),
            fullUri: $route->uri() !== '/' ? "/{$route->uri()}" : '/',
            name: $name,
            methods: $route->methods(),
            parameters: $route->parameterNames(),
        );

        $type->method = $type->setMethod();
        $type->nameCamel = $type->dashesToCamelCase($type->name);

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

    private function dashesToCamelCase(string $string, bool $capitalizeFirstCharacter = false)
    {
        $str = str_replace('-', '', ucwords($string, '.'));

        if (! $capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }

        $str = str_replace('.', '', $str);

        return ucfirst($str);
    }
}
