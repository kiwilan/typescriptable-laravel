<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Illuminate\Routing\Route;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route as FacadesRoute;
use Kiwilan\Typescriptable\TypescriptableConfig;

class TypeRouteGenerator
{
    /** @var Collection<string, TypeRoute> */
    protected Collection $routes;

    public static function make(string $list = null): self
    {
        $self = new self();
        $self->routes = $self->parse($list);

        return $self;
    }

    public function get(): Collection
    {
        return $this->routes;
    }

    /**
     * @return Collection<string, TypeRoute>
     */
    private function parse(string $list = null): Collection
    {
        /** @var Collection<int, Route> $items */
        $items = collect([]);
        if ($list) {
            $content = file_get_contents($list);
            $content = json_decode($content, true);
            $content = collect($content);

            $content->each(function (array $route) use (&$items) {
                $methods = $route['methods'] ?? [];
                $uri = $route['uri'] ?? '';

                $route = new Route(
                    methods: $methods,
                    uri: $uri,
                    action: [
                        'middleware' => $route['middleware'] ?? [],
                        'as' => $route['name'] ?? null,
                    ],
                );

                $route->uri = $uri;

                $items->push($route);
            });
        } else {
            /** @var Collection<int, Route> $items */
            $items = collect(FacadesRoute::getRoutes());
        }

        /** @var TypeRoute[] $routes */
        $routes = $items->mapWithKeys(function (Route $route) {
            $id = TypeRoute::generateId($route);

            return [$id => $route];
        })
            ->filter()
            ->map(fn (Route $route) => TypeRoute::make($route))
            ->toArray();

        // for testing
        // file_put_contents(
        //     database_path('routes.json'),
        //     json_encode(collect(FacadesRoute::getRoutes())->toArray(), JSON_PRETTY_PRINT)
        // );
        $list = [];

        foreach ($routes as $id => $route) {
            if (! $this->skipRouteName($route)) {
                $list[$id] = $route;
            }
        }

        foreach ($list as $route) {
            if ($this->skipRoutePath($route)) {
                unset($list[$route->name()]);
            }
        }

        return collect($list);
    }

    private function skipRouteName(TypeRoute $route): bool
    {
        $skip_name = [];
        $skippable_name = TypescriptableConfig::routesSkipName();

        foreach ($skippable_name as $item) {
            $item = str_replace('.*', '', $item);
            array_push($skip_name, $item);
        }

        foreach ($skip_name as $type => $item) {
            if (str_starts_with($route->name(), $item)) {
                return true;
            }
        }

        return false;
    }

    private function skipRoutePath(TypeRoute $route): bool
    {
        $skip_path = [];
        $skippable_path = TypescriptableConfig::routesSkipPath();

        foreach ($skippable_path as $item) {
            $item = str_replace('/*', '', $item);
            array_push($skip_path, $item);
        }

        foreach ($skip_path as $type => $item) {
            if (str_starts_with($route->uri(), $item)) {
                return true;
            }
        }

        return false;
    }
}
