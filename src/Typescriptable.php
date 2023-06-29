<?php

namespace Kiwilan\Typescriptable;

use Kiwilan\Typescriptable\Typed\EloquentType;
use Kiwilan\Typescriptable\Typed\RouteType;
use Kiwilan\Typescriptable\Typed\SettingType;

class Typescriptable
{
    public static function models(string $modelsPath, string $outputPath): EloquentType
    {
        return EloquentType::make($modelsPath, $outputPath);
    }

    public static function routes(string $routeList, string $outputPath): RouteType
    {
        return RouteType::make($routeList, $outputPath);
    }

    public static function settings(string $settingsPath, string $outputPath, string $extends): SettingType
    {
        return SettingType::make($settingsPath, $outputPath, $extends);
    }
}
