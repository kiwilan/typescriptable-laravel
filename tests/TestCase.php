<?php

namespace Kiwilan\TypeableModels\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kiwilan\TypeableModels\TypeableModelsServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kiwilan\\TypeableModels\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            TypeableModelsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-typeable-models_table.php.stub';
        $migration->up();
        */
    }
}
