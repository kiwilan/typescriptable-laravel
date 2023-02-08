<?php

namespace Kiwilan\Typescriptable\Services;

use Kiwilan\Typescriptable\Commands\TypescriptableModelsCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableZiggyCommand;
use Kiwilan\Typescriptable\Services\Typescriptable\EloquentType;
use Kiwilan\Typescriptable\Services\Typescriptable\ZiggyType;

class TypescriptableService
{
    public static function models(TypescriptableModelsCommand $command): EloquentType
    {
        $models = EloquentType::make($command);

        return $models;
    }

    public static function ziggy(TypescriptableZiggyCommand $command): ZiggyType
    {
        $ziggy = ZiggyType::make($command);

        return $ziggy;
    }
}
