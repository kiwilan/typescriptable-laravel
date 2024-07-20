<?php

namespace Kiwilan\Typescriptable\Typed;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Typed\Route\RouteGenerator;
use Kiwilan\Typescriptable\Typed\Route\RouteList;
use Kiwilan\Typescriptable\Typed\Route\RouteTypes;
use Kiwilan\Typescriptable\TypescriptableConfig;

class RouteType
{
    protected function __construct(
        public string $path,
        public string $pathList,
    ) {
    }

    public static function make(?string $jsonOutput = null, bool $withList = false, ?string $outputPath = null): self
    {
        $filename = TypescriptableConfig::routesFilename();
        $filenameRoutes = TypescriptableConfig::routesFilenameList();

        $routes = RouteGenerator::make($jsonOutput)->get();
        $routeTypes = RouteTypes::make($routes)->get();
        $routeList = RouteList::make($routes)->get();

        $file = $outputPath.DIRECTORY_SEPARATOR.$filename;
        $fileRoutes = $outputPath.DIRECTORY_SEPARATOR.$filenameRoutes;

        if (! $outputPath) {
            $file = TypescriptableConfig::setPath($filename);
            $fileRoutes = TypescriptableConfig::setPath($filenameRoutes);
        }

        $self = new self($file, $fileRoutes);

        $self->print($file, $routeTypes);
        if ($withList) {
            $self->print($fileRoutes, $routeList);
        }

        return $self;
    }

    private function print(string $path, string $content): void
    {
        if (! File::exists(dirname($path))) {
            File::makeDirectory(dirname($path));
        }

        File::delete($path);

        File::put($path, $content);
    }
}
