<?php

namespace Kiwilan\TypeableModels;

use Kiwilan\TypeableModels\Commands\TypeableModelsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TypeableModelsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-typeable-models')
            ->hasConfigFile()
            ->hasCommand(TypeableModelsCommand::class);
    }
}
