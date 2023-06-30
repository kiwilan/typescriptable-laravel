<?php

namespace Kiwilan\Typescriptable\Typed;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Route\TypeRouteGenerator;
use Kiwilan\Typescriptable\Typed\Route\TypeRouteListTs;
use Kiwilan\Typescriptable\Typed\Route\TypeRouteTs;
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

        $routes = TypeRouteGenerator::make($routeList)->get();
        $tsTypeRoute = TypeRouteTs::make($routes)->get();
        $tsRoute = TypeRouteListTs::make($routes)->get();

        $file = $outputPath.DIRECTORY_SEPARATOR.$filename;
        $fileRoutes = $outputPath.DIRECTORY_SEPARATOR.$filenameRoutes;

        if (! $outputPath) {
            $file = TypescriptableConfig::setPath($filename);
            $fileRoutes = TypescriptableConfig::setPath($filenameRoutes);
        }

        File::put($file, $tsTypeRoute);
        File::put($fileRoutes, $tsRoute);

        return new self($file, $fileRoutes);
    }
}
