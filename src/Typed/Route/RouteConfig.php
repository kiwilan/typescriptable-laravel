<?php

namespace Kiwilan\Typescriptable\Typed\Route;

use Kiwilan\Typescriptable\TypescriptableConfig;

class RouteConfig
{
    /**
     * @param  string[]  $namesToSkip
     * @param  string[]  $pathsToSkip
     */
    public function __construct(
        public ?string $filenameTypes = null,
        public ?string $filenameList = null,
        public bool $printList = true,
        public ?array $json = null,
        public array $namesToSkip = [],
        public array $pathsToSkip = [],
    ) {
        $this->filenameTypes = TypescriptableConfig::routesFilename();
        $this->filenameList = TypescriptableConfig::routesFilenameList();
        $this->printList = TypescriptableConfig::routesPrintList();
        $this->namesToSkip = TypescriptableConfig::routesSkipName();
        $this->pathsToSkip = TypescriptableConfig::routesSkipPath();
    }
}
