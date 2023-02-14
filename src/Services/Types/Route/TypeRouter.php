<?php

namespace Kiwilan\Typescriptable\Services\Types\Route;

use Kiwilan\Typescriptable\TypescriptableConfig;

class TypeRouter
{
    protected function __construct(
        protected array $routes = [],
        protected $router = null,
    ) {
    }

    public static function make(): string
    {
        // $routes = collect(app('router')->getRoutes())
        //     ->mapWithKeys(function ($route) {
        //         return [$route->getName() => $route];
        //     })
        //     ->filter()
        //     ->map(function ($route) {
        //         return [
        //             'uri' => $route->uri,
        //             'methods' => $route->methods,
        //         ];
        //     })
        // ;
        // $routes = $routes->join("\n");

        $type = new self();
        $type->routes = $type->setRoutes();

        $type->router = collect($type->routes)
            ->map(function ($route, $key) {
                $methods = json_encode($route['methods'] ?? []);

                return "  '{$key}': { 'uri': '{$route['uri']}', 'methods': {$methods} }";
            })
            ->join("\n");

        // dump($type);

        // return <<<typescript
        // declare type LaravelRoutes = {
        //     {$routes}
        // };
        // typescript;

        return '';
    }

    private function setRoutes(): array
    {
        $routes = collect(app('router')->getRoutes())
            ->mapWithKeys(function ($route) {
                return [$route->getName() => $route];
            })
            ->filter()
            ->map(function ($route) {
                return [
                    'uri' => $route->uri,
                    'methods' => $route->methods,
                ];
            })
            ->toArray();

        $list = [];

        foreach ($routes as $key => $route) {
            if (! $this->skipRouteName($key)) {
                $list[$key] = $route;
            }
        }

        foreach ($list as $key => $route) {
            if ($this->skipRoutePath($route)) {
                unset($list[$key]);
            }
        }

        dump($list);

        return $list;
    }

    private function skipRouteName(string $route): bool
    {
        $skip_name = [];
        $skippable_name = TypescriptableConfig::routesSkipName();

        foreach ($skippable_name as $item) {
            $item = str_replace('.*', '', $item);
            array_push($skip_name, $item);
        }

        foreach ($skip_name as $type => $item) {
            if (str_starts_with($route, $item)) {
                return true;
            }
        }

        return false;
    }

    private function skipRoutePath(array $route): bool
    {
        $skip_path = [];
        $skippable_path = TypescriptableConfig::routesSkipPath();

        foreach ($skippable_path as $item) {
            $item = str_replace('/*', '', $item);
            array_push($skip_path, $item);
        }

        foreach ($skip_path as $type => $item) {
            if (str_starts_with($route['uri'], $item)) {
                return true;
            }
        }

        return false;
    }
}
