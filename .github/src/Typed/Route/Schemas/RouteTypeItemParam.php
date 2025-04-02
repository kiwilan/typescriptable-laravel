<?php

namespace Kiwilan\Typescriptable\Eloquent\Route\Schemas;

class RouteTypeItemParam
{
    public function __construct(
        protected ?string $name = null,
        protected ?string $type = 'string',
        protected bool $required = true,
        protected ?string $default = null,
    ) {}

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
