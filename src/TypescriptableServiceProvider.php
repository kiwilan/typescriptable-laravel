<?php

namespace Kiwilan\Typescriptable;

use Kiwilan\Typescriptable\Commands\TypescriptableCommand;
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
            ->hasCommand(TypescriptableCommand::class);
    }
}
