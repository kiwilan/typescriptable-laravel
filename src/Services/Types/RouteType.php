<?php

namespace Kiwilan\Typescriptable\Services\Types;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Services\Types\Route\TypeRouter;
use Kiwilan\Typescriptable\TypescriptableConfig;

class RouteType
{
    protected function __construct(
        public string $path,
        public string $pathList,
    ) {
    }

    public static function make(): self
    {
        $path = TypescriptableConfig::outputPath();
        $filename = TypescriptableConfig::routesFilename();
        $filenameRoutes = TypescriptableConfig::routesFilenameList();

        $type = TypeRouter::make();

        if (! File::isDirectory($path)) {
            File::makeDirectory($filename);
        }

        $file = "{$path}/{$filename}";
        $fileRoutes = "{$path}/{$filenameRoutes}";

        File::put($file, $type->typescript());
        File::put($fileRoutes, $type->typescriptRoutes());

        return new self($file, $fileRoutes);
    }
}
