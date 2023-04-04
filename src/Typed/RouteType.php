<?php

namespace Kiwilan\Typescriptable\Typed;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Route\TypeRouter;
use Kiwilan\Typescriptable\TypescriptableConfig;

class RouteType
{
    protected function __construct(
        public string $path,
        public string $pathList,
    ) {
    }

    public static function make(?string $routeList = null, ?string $outputPath = null): self
    {
        $filename = TypescriptableConfig::routesFilename();
        $filenameRoutes = TypescriptableConfig::routesFilenameList();

        $type = TypeRouter::make($routeList);

        if ($outputPath) {
            $file = $outputPath.DIRECTORY_SEPARATOR.$filename;
            $fileRoutes = $outputPath.DIRECTORY_SEPARATOR.$filenameRoutes;
        } else {
            $file = TypescriptableConfig::setPath($filename);
            $fileRoutes = TypescriptableConfig::setPath($filenameRoutes);
        }

        File::put($file, $type->typescript());
        File::put($fileRoutes, $type->typescriptRoutes());

        return new self($file, $fileRoutes);
    }
}
