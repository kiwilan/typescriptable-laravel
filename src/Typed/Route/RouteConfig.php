<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Kiwilan\Typescriptable\TypescriptableConfig;

class RouteConfig
{
    public function __construct(
        public ?string $pathTypes = null,
        public ?string $pathList = null,
        public ?array $json = null,
    ) {
        $this->pathTypes = $pathTypes.DIRECTORY_SEPARATOR.TypescriptableConfig::routesFilename();
        $this->pathList = $pathList.DIRECTORY_SEPARATOR.TypescriptableConfig::routesFilenameList();
    }
}
