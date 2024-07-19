<?php

namespace Kiwilan\Typescriptable;

use Kiwilan\Typescriptable\Commands\ModelListCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableModelsCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableSettingsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TypescriptableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('typescriptable')
            ->hasConfigFile()
            ->hasCommands([
                TypescriptableCommand::class,
                TypescriptableModelsCommand::class,
                TypescriptableRoutesCommand::class,
                TypescriptableSettingsCommand::class,
                ModelListCommand::class,
            ]);
    }
}
