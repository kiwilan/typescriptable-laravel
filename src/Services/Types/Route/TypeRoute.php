<?php

namespace Kiwilan\Typescriptable\Services\Types\Route;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use Kiwilan\Typescriptable\TypescriptableConfig;

class TypeRoute
{
    protected function __construct(
        protected string $id,
        protected string $uri,
        protected ?string $fullUri = null,
        protected ?string $name = null,
        protected ?string $namePath = null,
        protected ?string $nameCamel = null,
        protected ?string $namePathCamel = null,
        protected ?string $routeName = null,
        protected array $methods = [],
        protected string $method = 'GET',
        /** @var TypeRouteParam[] */
        protected array $parameters = [],
    ) {
    }

    public static function make(Route $route): self
    {
        $path = $route->uri();
        $cleanPath = Str::replace('/', '.', $path);
        $cleanPath = Str::replace('{', ' ', $cleanPath);
        $cleanPath = Str::replace('}', ' ', $cleanPath);
        $cleanPath = Str::replace('?', ' ', $cleanPath);
        $camelPath = Str::camel($cleanPath);

        $name = $route->getName();
        $namePath = $camelPath;

        if (! $name) {
            $name = $route->uri();
        }

        $type = new self(
            id: self::generateId($route),
            uri: $route->uri(),
            fullUri: $route->uri() !== '/' ? "/{$route->uri()}" : '/',
            name: $name,
            namePath: $namePath,
            methods: $route->methods(),
            parameters: TypeRouteParam::make($route),
        );

        $type->method = $type->setMethod();
        $type->nameCamel = $type->dashesToCamelCase($type->name);
        $type->namePathCamel = $type->dashesToCamelCase($type->namePath).ucfirst($type->method);
        $type->routeName = $type->namePathCamel;

        return $type;
    }

    public static function generateId(Route $route): string
    {
        $methods = $route->methods();
        $methods = implode(' ', $methods);
        $id = "{$methods} {$route->uri()}";

        return Str::slug($id);
    }

    public function id(): string
    {
        return $this->id;
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

    public function namePath(): ?string
    {
        return $this->namePath;
    }

    public function nameCamel(): ?string
    {
        return $this->nameCamel;
    }

    public function namePathCamel(): ?string
    {
        return $this->namePathCamel;
    }

    public function routeName(): ?string
    {
        return $this->routeName;
    }

    public function methods(): array
    {
        return $this->methods;
    }

    public function method(): string
    {
        return $this->method;
    }

    /**
     * @return TypeRouteParam[]
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    public function nameType(): string
    {
        return TypescriptableConfig::routesUsePath()
            ? $this->namePathCamel()
            : $this->nameCamel();
    }

    public function pathType(): string
    {
        return TypescriptableConfig::routesUsePath()
            ? $this->fullUri()
            : $this->name();
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
