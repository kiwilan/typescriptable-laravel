<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Illuminate\Support\Collection;

class TypeRouter
{
    /** @var Collection<string, TypeRoute> */
    protected Collection $routes;

    protected ?string $tsTypeRoute = null;

    protected ?string $tsRoute = null;

    public static function make(?string $routeList = null): self
    {
        $self = new self();
        $self->routes = TypeRouteGenerator::make($routeList)->routes();
        $self->tsTypeRoute = TypeRouteTs::make($self->routes)->content();
        $self->tsRoute = TypeRouteListTs::make($self->routes)->content();

        return $self;
    }

    public function tsTypeRoute(): ?string
    {
        return $this->tsTypeRoute;
    }

    public function tsRoute(): ?string
    {
        return $this->tsRoute;
    }
}
