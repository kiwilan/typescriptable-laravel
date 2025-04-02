<?php

namespace Kiwilan\Typescriptable\Eloquent\Route\Schemas;

use Illuminate\Support\Str;

class RouteTypeItem
{
    /**
     * @param  string[]  $methods
     * @param  string[]  $middlewares
     * @param  RouteTypeItemParam[]  $parameters
     */
    protected function __construct(
        protected ?string $domain,
        protected string $uri,
        protected ?string $name = null,
        protected ?string $action = null,
        protected string $methodMain = 'GET',
        protected array $methods = [],
        protected array $middlewares = [],
        protected array $parameters = [],
        protected ?string $id = null,
    ) {}

    /**
     * @param  array<string, mixed>  $route
     */
    public static function make(array $route): self
    {
        $method = $route['method'] ?? 'GET|HEAD';
        $methods = explode('|', $method);
        $uri = $route['uri'] ?? '/';

        $self = new self(
            domain: $route['domain'] ?? null,
            uri: $uri === '/' ? $uri : "/{$uri}",
            name: $route['name'] ?? null,
            action: $route['action'] ?? null,
            methodMain: $methods[0] ?? 'GET',
            methods: $methods,
            middlewares: $route['middleware'] ?? [],
        );
        $self->id = $self->generateId();
        $self->parameters = $self->parseParameters();

        if (! $self->name) {
            $name = str_replace('/', '.', $self->uri);
            $self->name = Str::slug($name, '.');
        }

        return $self;
    }

    public function domain(): ?string
    {
        return $this->domain;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function name(): ?string
    {
        return $this->name;
    }

    public function action(): ?string
    {
        return $this->action;
    }

    public function methodMain(): string
    {
        return $this->methodMain;
    }

    /**
     * @return string[]
     */
    public function methods(): array
    {
        return $this->methods;
    }

    /**
     * @return RouteTypeItemParam[]
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @return string[]
     */
    public function middlewares(): array
    {
        return $this->middlewares;
    }

    public function id(): string
    {
        return $this->id;
    }

    private function generateId(): string
    {
        $name = $this->uri;
        if ($this->name) {
            $name = $this->name;
        }
        $name = str_replace('/', '.', $name);
        $id = strtolower("{$this->methodMain} {$name}");

        return Str::slug($id, '.');
    }

    /**
     * @return RouteTypeItemParam[]
     */
    private function parseParameters(): array
    {
        $params = [];
        preg_match_all('/\{([^\}]+)\}/', $this->uri, $params);

        if (array_key_exists(1, $params)) {
            $params = $params[1];
        }

        /** @var RouteTypeItemParam[] */
        $items = [];

        foreach ($params as $param) {
            $name = $param;
            $required = true;

            if (str_contains($param, '?')) {
                $name = str_replace('?', '', $param);
                $required = false;
            }

            $items[] = new RouteTypeItemParam(
                name: $name,
                required: $required,
            );
        }

        return $items;
    }
}
