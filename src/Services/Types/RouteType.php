<?php

namespace Kiwilan\Typescriptable\Services\Types;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand;
use Kiwilan\Typescriptable\Services\Types\Route\TypeRouter;
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
        $filenameRoutes = TypescriptableConfig::filenameRoutesList();

        $type = TypeRouter::make();

        if (! File::isDirectory($path)) {
            File::makeDirectory($filename);
        }

        $file = "{$path}/{$filename}";
        $fileRoutes = "{$path}/{$filenameRoutes}";

        File::put($file, $type->typescript());
        File::put($fileRoutes, $type->typescriptRoutes());

        return new self($command);
    }
}
