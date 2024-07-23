<?php

namespace Kiwilan\Typescriptable\Typed\Route\Printer;

use Illuminate\Support\Collection;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItem;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItemParam;
use Kiwilan\Typescriptable\Typescriptable;

class PrinterToList
{
    /**
     * @param  Collection<string, RouteTypeItem>  $routes
     */
    public static function make(Collection $routes): string
    {
        $self = new self();

        $contents = [];
        foreach ($routes as $route) {
            $contents[] = $self->typescript($route);
        }

        return $self->template(implode("\n", $contents));
    }

    private function template(string $contents): string
    {
        $head = Typescriptable::head();
        $appUrl = config('app.url');

        return <<<typescript
        {$head}
        const Routes: Record<App.Route.Name, App.Route.Link> = {
        {$contents},
        }

        declare global {
          interface Window {
            Routes: Record<App.Route.Name, App.Route.Link>
          }
        }

        const appUrl = '{$appUrl}'

        if (typeof window !== 'undefined') {
          window.Routes = Routes
        }

        export { Routes, appUrl }

        typescript;
    }

    private function typescript(RouteTypeItem $route): string
    {
        $params = collect($route->parameters())
            ->map(fn (RouteTypeItemParam $param) => "{$param->name()}: 'string',");

        if ($params->isEmpty()) {
            $params = 'params: undefined';
        } else {
            $params = $params->join(' ');
            if (str_contains($params, ',')) {
                $paramsExplode = explode(',', $params);
                $paramsExplode = array_map(fn ($param) => trim($param), $paramsExplode);
                $paramsExplode = array_filter($paramsExplode, fn ($param) => ! empty($param));
                $paramsExplode = array_map(fn ($param) => "{$param},", $paramsExplode);
                $params = implode("\n      ", $paramsExplode);
            }
            $params = <<<typescript
                  params: {
                        {$params}
                      }
                  typescript;
        }

        $methods = $route->methods();
        $methods = array_filter($methods, fn ($method) => $method !== 'HEAD');
        $methods = array_map(fn ($method) => "'{$method}'", $methods);
        $methods = implode(', ', $methods);

        return <<<typescript
          '{$route->name()}': {
            name: '{$route->name()}',
            path: '{$route->uri()}',
            {$params},
            methods: [{$methods}],
          }
        typescript;
    }
}
