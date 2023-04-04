<?php

namespace Kiwilan\Typescriptable\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\TypescriptableServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use PDO;
use stdClass;

class TestCase extends Orchestra
{
    // protected static PDO $pdo;

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kiwilan\\Typescriptable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    // public static function setUpBeforeClass(): void
    // {
    //     $database = self::getDatabase();

    //     $dsn = "{$database['driver']}:host={$database['host']};port={$database['port']};dbname={$database['database']}";

    //     self::$pdo = new PDO($dsn, $database['user'], $database['password']);
    // }

    /**
     * Get the database connection.
     *
     * @return object `{ driver: string, host: string, port: string, url: string, database: string, user: string, password: string }`
     */
    private static function getDatabase(?string $driver = null): object
    {
        $database = new stdClass();

        $dotenv = Dotenv::createMutable(getcwd());
        $data = $dotenv->load();

        if ($driver) {
            $database->driver = strtolower($driver);
        } else {
            $database->driver = $data['DB_CONNECTION'] ? $data['DB_CONNECTION'] : getenv('DB_CONNECTION');
        }

        if (! $database->driver) {
            throw new \Exception('No database driver specified.');
        }
        $connection = strtoupper($database->driver);

        $database->host = $data["DB_{$connection}_HOST"] ? $data["DB_{$connection}_HOST"] : getenv("DB_{$connection}_HOST");
        $database->port = $data["DB_{$connection}_PORT"] ? $data["DB_{$connection}_PORT"] : getenv("DB_{$connection}_PORT");
        $database->user = $data["DB_{$connection}_USER"] ? $data["DB_{$connection}_USER"] : getenv("DB_{$connection}_USER");
        $database->password = $data["DB_{$connection}_PASSWORD"] ? $data["DB_{$connection}_PASSWORD"] : getenv("DB_{$connection}_PASSWORD");
        $database->database = $data["DB_{$connection}_DATABASE"] ? $data["DB_{$connection}_DATABASE"] : getenv("DB_{$connection}_DATABASE");

        $database->url = null;
        if ($database->host && $database->user && $database->port) {
            $database->url = "{$database->driver}://{$database->user}:{$database->password}@{$database->host}:{$database->port}/";
        }

        return $database;
    }

    protected function getPackageProviders($app)
    {
        return [
            TypescriptableServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        self::setupDatabase();
    }

    public static function setupDatabase(?string $driver = null): void
    {
        $database = self::getDatabase($driver);

        $configs = [
            'sqlite' => [
                'prefix' => '',
            ],
            'mysql' => [
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
            ],
            'pgsql' => [
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                'search_path' => 'public',
                'sslmode' => 'prefer',
            ],
            'sqlsrv' => [
                'charset' => 'utf8',
                'prefix' => '',
                'prefix_indexes' => true,
                // 'encrypt' => env('DB_ENCRYPT', 'yes'),
                // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
            ],
        ];

        config()->set('database.default', $database->driver);
        config()->set("database.connections.{$database->driver}", [
            'driver' => $database->driver,
            'database' => $database->database,
            'url' => $database->url,
            'host' => $database->host,
            'port' => $database->port,
            'database' => $database->database,
            'username' => $database->user,
            'password' => $database->password,
            ...$configs[$database->driver],
        ]);

        Schema::dropIfExists($database->database);
        Schema::dropAllTables($database->database);

        $migration = include __DIR__.'/Data/database/migrations/create_models_tables.php';
        $migration->up();
    }
}
