<?php

namespace Kiwilan\Typescriptable\Eloquent\Route;

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
        if (! $this->filenameTypes) {
            $this->filenameTypes = TypescriptableConfig::routesFilename();
        }

        if (! $this->filenameList) {
            $this->filenameList = TypescriptableConfig::routesFilenameList();
        }

        if (! $this->printList) {
            $this->printList = TypescriptableConfig::routesPrintList();
        }
        if (! $this->namesToSkip) {
            $this->namesToSkip = TypescriptableConfig::routesSkipName();
        }

        if (! $this->pathsToSkip) {
            $this->pathsToSkip = TypescriptableConfig::routesSkipPath();
        }
    }
}
