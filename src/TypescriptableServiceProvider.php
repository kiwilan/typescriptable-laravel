<?php

namespace Kiwilan\Typescriptable;

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
                // \Kiwilan\Typescriptable\Commands\TypescriptableCommand::class,
                // \Kiwilan\Typescriptable\Commands\TypescriptableEloquentCommand::class,
                // \Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand::class,
                // \Kiwilan\Typescriptable\Commands\TypescriptableSettingsCommand::class,
                // \Kiwilan\Typescriptable\Commands\EloquentListCommand::class,
            ]);
    }
}
