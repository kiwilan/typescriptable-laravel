<?php

namespace Kiwilan\Typeable;

use Kiwilan\Typeable\Commands\TypeableCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class TypeableServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-typeable')
            ->hasConfigFile()
            ->hasCommand(TypeableCommand::class);
    }
}
