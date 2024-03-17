<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Illuminate\Support\Collection;
use Kiwilan\Typescriptable\Typescriptable;

class TypeRouteListTs
{
    /** @var Collection<string, TypeRoute> */
    protected Collection $routes;

    protected ?string $typescript = null;

    /**
     * @param  Collection<string, TypeRoute>  $routes
     */
    public static function make(Collection $routes): self
    {
        $self = new self();

        $items = collect([]);
        $routes->each(function (TypeRoute $route) use ($items) {
            if ($items->has($route->name())) {
                $item = $items->get($route->name());
                $route = $route->addMethods($item->methods());
                $items->put($route->name(), $route);

                return;
            }

            $items->put($route->name(), $route);
        });
        $self->routes = $items;

        $self->typescript = $self->setTypescript();

        return $self;
    }

    public function get(): string
    {
        $head = Typescriptable::head();

        return <<<typescript
        {$head}
        const Routes: Record<App.Route.Name, App.Route.Link> = {
        {$this->typescript},
        }

        declare global {
          interface Window {
            Routes: Record<App.Route.Name, App.Route.Link>
          }
        }

        if (typeof window !== 'undefined') {
          if (typeof window !== undefined && typeof window?.Routes !== undefined)
            window?.Routes = Routes
        }

        export { Routes }

        typescript;
    }

    private function setTypescript(): string
    {
        $list = $this->routes->map(function (TypeRoute $route) {
            $params = collect($route->parameters())
                ->map(fn (TypeRouteParam $param) => "{$param->name()}: 'string',");

            if ($params->isEmpty()) {
                $params = 'params: undefined';
            } else {
                $params = $params->join(' ');
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
              path: '{$route->fullUri()}',
              {$params},
              methods: [{$methods}],
            }
          typescript;
        });

        return $list->join(",\n");
    }
}
