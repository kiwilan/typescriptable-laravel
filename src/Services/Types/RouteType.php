<?php

namespace Kiwilan\Typescriptable\Services\Types;

use Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand;
use Kiwilan\Typescriptable\TypescriptableConfig;

class RouteType
{
    protected function __construct(
        protected TypescriptableRoutesCommand $command,
    ) {
    }

    public static function make(TypescriptableRoutesCommand $command): self
    {
        $path = TypescriptableConfig::outputPath();
        $filename = TypescriptableConfig::filenameRoutes();

        $route = new self($command);

        return $route;
    }
}
