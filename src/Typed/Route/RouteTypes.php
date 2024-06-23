<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Closure;
use Illuminate\Support\Collection;
use Kiwilan\Typescriptable\Typed\Route\Models\TypeRoute;
use Kiwilan\Typescriptable\Typed\Route\Models\TypeRouteParam;
use Kiwilan\Typescriptable\Typescriptable;

class RouteTypes
{
    /** @var Collection<string, TypeRoute> */
    protected Collection $routes;

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
     * @param  Collection<string, TypeRoute>  $routes
     */
    public static function make(Collection $routes): self
    {
        $self = new self();
        $self->routes = $routes;
        $self->parse();

        return $self;
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
          };

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

    private function parse()
    {
        $this->tsNames = $this->setTsNames();
        $this->tsPaths = $this->setTsPaths();
        $this->tsParams = $this->setTsParams();
    }

    private function setTsNames(): string
    {
        $names = [];
        $this->collectRoutes(function (TypeRoute $route) use (&$names) {
            $names[] = "'{$route->name()}'";
        });

        $names = array_unique($names);
        sort($names);

        return implode(' | ', $names);
    }

    private function setTsPaths(): string
    {
        $uri = [];
        $this->collectRoutes(function (TypeRoute $route) use (&$uri) {
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

    private function setTsParams(): string
    {
        return $this->collectRoutes(function (TypeRoute $route) {
            $hasParams = count($route->parameters()) > 0;

            if ($hasParams) {
                $params = collect($route->parameters())
                    ->map(fn (TypeRouteParam $param) => "'{$param->name()}': App.Route.ParamType")
                    ->join("\n");

                return "    '{$route->name()}': {\n      {$params}\n    }";
            } else {
                return "    '{$route->name()}': never";
            }
        }, "\n");
    }

    private function collectRoutes(Closure $closure, ?string $join = null): string|Collection
    {
        $routes = $this->routes->map(fn (TypeRoute $route, string $key) => $closure($route, $key));

        if ($join) {
            return $routes->join($join);
        }

        return $routes;
    }
}
