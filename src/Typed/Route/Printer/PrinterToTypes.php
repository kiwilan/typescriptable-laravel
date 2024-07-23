<?php

namespace Kiwilan\Typescriptable\Typed\Route\Printer;

use Closure;
use Illuminate\Support\Collection;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItem;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItemParam;
use Kiwilan\Typescriptable\Typescriptable;

class PrinterToTypes
{
    // ROUTE NAMES

    protected ?string $tsNames = null;

    protected ?string $tsPaths = null;

    protected ?string $tsParams = null;

    // GLOBAL TYPES

    protected ?string $tsTypes = null;

    protected ?string $tsGlobalTypes = null;

    // TYPES

    protected ?string $tsGlobalTypesGet = null;

    protected ?string $tsGlobalTypesPost = null;

    protected ?string $tsGlobalTypesPut = null;

    protected ?string $tsGlobalTypesPatch = null;

    protected ?string $tsGlobalTypesDelete = null;

    /**
     * @param  Collection<string, RouteTypeItem>  $routes
     */
    protected function __construct(
        protected Collection $routes,
    ) {}

    /**
     * @param  Collection<string, RouteTypeItem>  $routes
     */
    public static function make(Collection $routes): string
    {
        $self = new self($routes);

        $self->tsNames = $self->typescriptNames();
        $self->tsPaths = $self->typescriptPaths();
        $self->tsParams = $self->typescriptParams();

        return $self->get();
    }

    public function get(): string
    {
        $this->tsNames = empty($this->tsNames) ? 'never' : $this->tsNames;
        $this->tsPaths = empty($this->tsPaths) ? 'never' : $this->tsPaths;

        $this->tsGlobalTypes = empty($this->tsGlobalTypes) ? 'never' : $this->tsGlobalTypes;
        $this->tsGlobalTypesGet = empty($this->tsGlobalTypesGet) ? 'never' : $this->tsGlobalTypesGet;
        $this->tsGlobalTypesPost = empty($this->tsGlobalTypesPost) ? 'never' : $this->tsGlobalTypesPost;
        $this->tsGlobalTypesPut = empty($this->tsGlobalTypesPut) ? 'never' : $this->tsGlobalTypesPut;
        $this->tsGlobalTypesPatch = empty($this->tsGlobalTypesPatch) ? 'never' : $this->tsGlobalTypesPatch;
        $this->tsGlobalTypesDelete = empty($this->tsGlobalTypesDelete) ? 'never' : $this->tsGlobalTypesDelete;

        $head = Typescriptable::head();

        return <<<typescript
        {$head}
        declare namespace App.Route {
          export type Name = {$this->tsNames};
          export type Path = {$this->tsPaths};
          export interface Params {
        {$this->tsParams}
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

    private function typescriptNames(): string
    {
        $names = [];
        $this->collectRoutes(function (RouteTypeItem $route) use (&$names) {
            $names[] = "'{$route->name()}'";
        });

        $names = array_unique($names);
        sort($names);

        return implode(' | ', $names);
    }

    private function typescriptPaths(): string
    {
        $uri = [];
        $this->collectRoutes(function (RouteTypeItem $route) use (&$uri) {
            if ($route->uri() === '/') {
                $uri[] = "'/'";

                return;
            }

            $uri[] = "'/{$route->uri()}'";
        });

        $uri = array_unique($uri);
        sort($uri);

        return implode(' | ', $uri);
    }

    private function typescriptParams(): string
    {
        return $this->collectRoutes(function (RouteTypeItem $route) {
            $hasParams = count($route->parameters()) > 0;

            if ($hasParams) {
                $params = collect($route->parameters())
                    ->map(fn (RouteTypeItemParam $param) => "'{$param->name()}': App.Route.ParamType")
                    ->join("\n");

                return "    '{$route->name()}': {\n      {$params}\n    }";
            } else {
                return "    '{$route->name()}': never";
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
