<?php

namespace Kiwilan\Typescriptable\Services;

use Kiwilan\Typescriptable\Commands\TypescriptableInertiaCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableModelsCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableZiggyCommand;
use Kiwilan\Typescriptable\Services\Types\EloquentType;
use Kiwilan\Typescriptable\Services\Types\InertiaType;
use Kiwilan\Typescriptable\Services\Types\RouteType;
use Kiwilan\Typescriptable\Services\Types\ZiggyType;

class TypescriptableService
{
    public static function models(TypescriptableModelsCommand $command): EloquentType
    {
        $models = EloquentType::make($command);

        return $models;
    }

    public static function route(TypescriptableRoutesCommand $command): RouteType
    {
        $route = RouteType::make($command);

        return $route;
    }

    public static function inertia(TypescriptableInertiaCommand $command): InertiaType
    {
        $inertia = InertiaType::make($command);

        return $inertia;
    }

    public static function ziggy(TypescriptableZiggyCommand $command): ZiggyType
    {
        $ziggy = ZiggyType::make($command);

        return $ziggy;
    }
}
