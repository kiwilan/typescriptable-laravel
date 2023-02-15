<?php

namespace Kiwilan\Typescriptable;

use Kiwilan\Typescriptable\Commands\TypescriptableInertiaCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableModelsCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand;
use Kiwilan\Typescriptable\Commands\TypescriptableZiggyCommand;
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
                TypescriptableModelsCommand::class,
                TypescriptableRoutesCommand::class,
                TypescriptableInertiaCommand::class,
                TypescriptableZiggyCommand::class,
            ]);
    }
}
