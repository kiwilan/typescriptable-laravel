<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Illuminate\Foundation\Console\RouteListCommand;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Typed\Route\Printer\PrinterRouteList;
use Kiwilan\Typescriptable\Typed\Route\Printer\PrinterRouteTypes;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItem;
use Kiwilan\Typescriptable\Typed\Utils\TypescriptableUtils;
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

    public static function make(RouteConfig $config = new RouteConfig): self
    {
        $self = new self($config);
        $self->routes = $self->parseRoutes();

        $self->typescriptTypes = PrinterRouteTypes::make($self->routes);
        $self->typescriptList = PrinterRouteList::make($self->routes);

        TypescriptableUtils::print($self->typescriptTypes, TypescriptableConfig::setPath($self->config->filenameTypes));
        if ($self->config->printList) {
            TypescriptableUtils::print($self->typescriptList, TypescriptableConfig::setPath($self->config->filenameList));
        }

        return $self;
    }

    public function config(): RouteConfig
    {
        return $this->config;
    }

    /**
     * @return Collection<string, RouteTypeItem>
     */
    public function routes(): Collection
    {
        return $this->routes;
    }

    public function typescriptList(): ?string
    {
        return $this->typescriptList;
    }

    public function typescriptTypes(): ?string
    {
        return $this->typescriptTypes;
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

        $skipNames = $this->toSkip($this->config->namesToSkip);
        $skipPaths = $this->toSkip($this->config->pathsToSkip);

        if (! $routes) {
            return collect();
        }
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
}
