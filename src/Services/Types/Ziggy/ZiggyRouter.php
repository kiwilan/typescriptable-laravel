<?php

namespace Kiwilan\Typescriptable\Services\Types\Ziggy;

use Tightenco\Ziggy\Ziggy;

class ZiggyRouter
{
    public static function make(): string
    {
        $ziggy = (new Ziggy(false, null));

        $routes = collect($ziggy->toArray()['routes'])
            ->map(function ($route, $key) {
                $methods = json_encode($route['methods'] ?? []);

                return "  '{$key}': { 'uri': '{$route['uri']}', 'methods': {$methods} }";
            })
            ->join("\n");

        return <<<typescript
        declare type LaravelRoutes = {
            {$routes}
        };
        typescript;
    }
}
