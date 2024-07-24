<?php

namespace Kiwilan\Typescriptable\Typed\Route\Printer;

use Closure;
use Illuminate\Support\Collection;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItem;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItemParam;
use Kiwilan\Typescriptable\Typescriptable;
use Kiwilan\Typescriptable\TypescriptableConfig;

class PrinterRouteTypes
{
    /**
     * @param  Collection<string, RouteTypeItem>  $routes
     */
    protected function __construct(
        protected Collection $routes,
        protected ?string $routeNames = null,
        protected ?string $routePaths = null,
        protected ?string $routeParams = null,
    ) {}

    /**
     * @param  Collection<string, RouteTypeItem>  $routes
     */
    public static function make(Collection $routes): string
    {
        $self = new self($routes);

        $routeNames = $self->parseRouteNames();
        $self->routeNames = empty($routeNames) ? 'never' : $routeNames;

        $routePaths = $self->parseRoutePaths();
        $self->routePaths = empty($routePaths) ? 'never' : $routePaths;

        $self->routeParams = $self->parseRouteParams();

        return $self->typescript();
    }

    public function typescript(): string
    {
        $head = Typescriptable::head();

        return <<<typescript
        {$head}
        declare namespace App.Route {
          export type Name = {$this->routeNames};
          export type Path = {$this->routePaths};
          export interface Params {
        {$this->routeParams}
          }

          export type Method = 'HEAD' | 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE'
          export type ParamType = string | number | boolean | undefined
          export interface Link { name: App.Route.Name; path: App.Route.Path; params?: App.Route.Params[App.Route.Name], methods: App.Route.Method[] }
          export interface RouteConfig<T extends App.Route.Name> {
            name: T
            params?: T extends keyof App.Route.Params ? App.Route.Params[T] : never
          }
        }

        typescript;
    }

    private function parseRouteNames(): string
    {
        $names = [];
        $this->collectRoutes(function (RouteTypeItem $route) use (&$names) {
            $name = TypescriptableConfig::routesUsePath()
                ? $route->uri()
                : $route->name();

            $names[] = "'{$name}'";
        });

        $names = array_unique($names);
        sort($names);

        return implode(' | ', $names);
    }

    private function parseRoutePaths(): string
    {
        $uri = [];
        $this->collectRoutes(function (RouteTypeItem $route) use (&$uri) {
            if ($route->uri() === '/') {
                $uri[] = "'/'";

                return;
            }

            $uri[] = "'{$route->uri()}'";
        });

        $uri = array_unique($uri);
        sort($uri);

        return implode(' | ', $uri);
    }

    private function parseRouteParams(): string
    {
        return $this->collectRoutes(function (RouteTypeItem $route) {
            $hasParams = count($route->parameters()) > 0;
            $name = TypescriptableConfig::routesUsePath()
                ? $route->uri()
                : $route->name();

            if ($hasParams) {
                $params = collect($route->parameters())
                    ->map(fn (RouteTypeItemParam $param) => "'{$param->name()}': App.Route.ParamType")
                    ->join("\n      ");

                return "    '$name}': {\n      {$params}\n    }";
            } else {
                return "    '{$name}': never";
            }
        }, "\n");
    }

    private function collectRoutes(Closure $closure, ?string $join = null): string|Collection
    {
        $routes = $this->routes->map(fn (RouteTypeItem $route, string $key) => $closure($route, $key));

        if ($join) {
            return $routes->join($join);
        }

        return $routes;
    }
}
