<?php

namespace Kiwilan\Typescriptable\Services;

use Kiwilan\Typescriptable\Services\Types\EloquentType;
use Kiwilan\Typescriptable\Services\Types\InertiaType;
use Kiwilan\Typescriptable\Services\Types\RouteType;

class TypescriptableService
{
    public static function models(): EloquentType
    {
        $models = EloquentType::make();

        return $models;
    }

    public static function route(): RouteType
    {
        $route = RouteType::make();

        return $route;
    }

    public static function inertia(): InertiaType
    {
        $inertia = InertiaType::make();

        return $inertia;
    }
}
