<?php

namespace Kiwilan\Typescriptable\Typed;

use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Route\Printer\PrinterToList;
use Kiwilan\Typescriptable\Typed\Route\Printer\PrinterToTypes;
use Kiwilan\Typescriptable\Typed\Route\RouteConfig;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItem;
use Kiwilan\Typescriptable\TypescriptableConfig;

class RouteType
{
    /**
     * @param  Collection<string, RouteTypeItem>  $routes
     */
    protected function __construct(
        protected RouteConfig $config,
        protected ?Collection $routes = null,
        protected ?string $typescriptList = null,
        protected ?string $typescriptTypes = null,
    ) {}

    public static function make(RouteConfig $config): self
    {
        $self = new self($config);
        $self->routes = $self->parseRoutes();

        $self->typescriptList = PrinterToList::make($self->routes);
        ray($self->typescriptList);
        $self->typescriptTypes = PrinterToTypes::make($self->routes);
        ray($self->typescriptTypes);

        // handle TypescriptableConfig::routesUsePath()

        // if (! $outputPath) {
        //     $file = TypescriptableConfig::setPath($filename);
        //     $fileRoutes = TypescriptableConfig::setPath($filenameRoutes);
        // }

        // $self->print($file, $routeTypes);
        // if ($withList) {
        //     $self->print($fileRoutes, $routeList);
        // }

        return $self;
    }

    /**
     * @return Collection<string, RouteTypeItem>
     */
    private function parseRoutes(): Collection
    {
        if ($this->config->json === null) {
            Artisan::call(RouteListCommand::class);
            $json = Artisan::output();
            $this->config->json = json_decode($json, true);
        }

        $routes = $this->config->json;

        $skipNames = $this->toSkip(TypescriptableConfig::routesSkipName());
        $skipPaths = $this->toSkip(TypescriptableConfig::routesSkipPath());

        $routes = array_filter($routes, fn ($route) => $this->filterBy($route, 'uri', $skipPaths));
        $routes = array_filter($routes, fn ($route) => $this->filterBy($route, 'name', $skipNames));
        $routes = array_values($routes);

        $collect = collect();
        foreach ($routes as $route) {
            $item = RouteTypeItem::make($route);
            $collect->put($item->id(), $item);
        }

        return $collect;
    }

    /**
     * Get the routes to skip.
     *
     * @param  string[]  $toSkip
     * @return string[]
     */
    private function toSkip(array $toSkip): array
    {
        $items = [];
        foreach ($toSkip as $item) {
            $item = str_replace('/*', '', $item);
            $item = str_replace('.*', '', $item);
            array_push($items, $item);
        }

        return $items;
    }

    private function filterBy(array $route, string $attribute, array $toSkip): bool
    {
        foreach ($toSkip as $skip) {
            if (str_starts_with($route[$attribute], $skip)) {
                return false;
            }
        }

        return true;
    }

    private function print(string $path, string $content): void
    {
        if (! File::exists(dirname($path))) {
            File::makeDirectory(dirname($path));
        }

        File::delete($path);

        File::put($path, $content);
    }
}
