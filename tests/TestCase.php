<?php

namespace Kiwilan\Typescriptable\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\TypescriptableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use PDO;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kiwilan\\Typescriptable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            TypescriptableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $type = 'sqlite'; // sqlite / mysql / pgsql / sqlsrv
        $this->setDatabase($type);

        $migration = include __DIR__.'/Data/database/migrations/create_models_tables.php';
        $migration->up();
    }

    private function setDatabase(string $type = 'sqlite'): void
    {
        if ($type === 'sqlite') {
            config()->set('database.default', 'sqlite');
            config()->set('database.connections.sqlite', [
                'driver' => 'sqlite',
                'database' => ':memory:',
                'prefix' => '',
            ]);
        }

        if ($type === 'mysql') {
            config()->set('database.default', 'mysql');
            config()->set('database.connections.mysql', [
                'driver' => 'mysql',
                'database' => 'testing',
                'url' => 'mysql://root@127.0.0.1:3306/',
                'host' => '127.0.0.1',
                'port' => '3306',
                'database' => 'testing',
                'username' => 'root',
                'password' => '',
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ]);

            Schema::dropAllTables('testing');
        }

        if ($type === 'pgsql') {
            config()->set('database.default', 'pgsql');
            config()->set('database.connections.pgsql', [
                'driver' => 'pgsql',
                'url' => 'pgsql://postgres@127.0.0.1:5432/',
                'host' => '127.0.0.1',
                'port' => '5432',
                'database' => 'testing',
                'username' => 'postgres',
                'password' => '',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'search_path' => 'public',
                'sslmode' => 'prefer',
            ]);

            Schema::dropAllTables('testing');
        }

        if ($type === 'sqlsrv') {
            config()->set('database.default', 'sqlsrv');
            config()->set('database.connections.sqlsrv', [
                'driver' => 'sqlsrv',
                'url' => 'sqlsrv://root@127.0.0.1:1433/',
                'host' => 'localhost',
                'port' => '1433',
                'database' => 'testing',
                'username' => 'root',
                'password' => '',
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                // 'encrypt' => env('DB_ENCRYPT', 'yes'),
                // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
            ]);
        }
    }
}
