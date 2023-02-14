<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable\Ziggy;

class ZiggyRouter
{
    public static function make(): string
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
            });
        $routes = $routes->join("\n");

        // $routes = collect($ziggy->toArray()['routes'])
        //     ->map(function ($route, $key) {
        //         $methods = json_encode($route['methods'] ?? []);

        //         return "  '{$key}': { 'uri': '{$route['uri']}', 'methods': {$methods} }";
        //     })
        //     ->join("\n");

        return <<<typescript
        declare type LaravelRoutes = {
            {$routes}
        };
        typescript;
    }
}
