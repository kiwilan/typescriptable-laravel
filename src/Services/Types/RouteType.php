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
        $filename = TypescriptableConfig::routesFilename();
        $filenameRoutes = TypescriptableConfig::routesFilenameList();

        $type = TypeRouter::make();

        $file = TypescriptableConfig::setPath($filename);
        $fileRoutes = TypescriptableConfig::setPath($filenameRoutes);

        File::put($file, $type->typescript());
        File::put($fileRoutes, $type->typescriptRoutes());

        return new self($file, $fileRoutes);
    }
}
