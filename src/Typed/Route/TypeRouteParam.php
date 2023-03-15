<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Illuminate\Routing\Route;

class TypeRouteParam
{
    protected function __construct(
        protected string $uri,
        protected ?string $name = null,
        protected ?string $type = 'string',
        protected bool $required = true,
        protected ?string $default = null,
    ) {
    }

    /**
     * @return TypeRouteParam[]
     */
    public static function make(Route $route): array
    {
        $params = [];
        preg_match_all('/\{([^\}]+)\}/', $route->uri, $params);

        if (array_key_exists(1, $params)) {
            $params = $params[1];
        }

        /** @var TypeRouteParam[] */
        $list = [];

        foreach ($params as $param) {
            $name = $param;
            $required = true;

            if (str_contains($param, '?')) {
                $name = str_replace('?', '', $param);
                $required = false;
            }

            $list[] = new TypeRouteParam(
                uri: $route->uri,
                name: $name,
                required: $required,
            );
        }

        return $list;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): string
    {
        return $this->type;
    }

    public function required(): bool
    {
        return $this->required;
    }

    public function default(): ?string
    {
        return $this->default;
    }
}
