<?php

namespace Kiwilan\Typescriptable\Tests;

use Dotenv\Dotenv;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Schema;
use Kiwilan\Typescriptable\Tests\Data\Utils\Driver;
use Kiwilan\Typescriptable\TypescriptableServiceProvider;
use MongoDB\Laravel\MongoDBServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use PDO;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
        self::init();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Kiwilan\\Typescriptable\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    public static function pdo(Driver $driver): PDO
    {
        if (! $driver->name) {
            throw new \Exception('No database driver specified.');
        }

        $dsn = "{$driver->name}:host={$driver->host};port={$driver->port};dbname={$driver->database}";

        if ($driver->name === 'sqlsrv') {
            $pdo = new PDO("sqlsrv:Server={$driver->host},{$driver->port};Database={$driver->database}", $driver->user, $driver->password);
        } else {
            $pdo = new PDO($dsn, $driver->user, $driver->password);
        }
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }

    /**
     * Get the database connection.
     */
    private static function getDriver(?string $type = null): Driver
    {
        $driver = new Driver;

        $dotenv = Dotenv::createMutable(getcwd());
        $data = $dotenv->load();

        if ($type) {
            $driver->name = strtolower($type);
        } else {
            $driver->name = $data['DB_CONNECTION'] ? $data['DB_CONNECTION'] : getenv('DB_CONNECTION');
        }

        if (! $driver->name) {
            throw new \Exception('No database driver specified.');
        }
        $connection = strtoupper($driver->name);

        $host = "DB_{$connection}_HOST";
        $port = "DB_{$connection}_PORT";
        $user = "DB_{$connection}_USER";
        $password = "DB_{$connection}_PASSWORD";
        $databaseName = "DB_{$connection}_DATABASE";
        $prefix = 'DB_PREFIX';

        $driver->host = $data[$host] ? $data[$host] : getenv($host);
        $driver->port = $data[$port] ? $data[$port] : getenv($port);
        $driver->user = $data[$user] ? $data[$user] : getenv($user);
        $driver->password = $data[$password] ? $data[$password] : getenv($password);
        $driver->database = $data[$databaseName] ? $data[$databaseName] : getenv($databaseName);
        $driver->prefix = $data[$prefix] ? $data[$prefix] : getenv($prefix);

        $driver->url = null;
        if ($driver->host && $driver->user && $driver->port) {
            if ($driver->name === 'sqlsrv') {
                $driver->url = "sqlsrv:Server={$driver->host},{$driver->port};Database={$driver->database}";
            } else {
                $driver->url = "{$driver->name}://{$driver->user}:{$driver->password}@{$driver->host}:{$driver->port}/";
            }
        }

        return $driver;
    }

    protected function getPackageProviders($app)
    {
        return [
            TypescriptableServiceProvider::class,
            MongoDBServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        self::setupDatabase();
    }

    public static function init()
    {
        config()->set('media-library.media_model', \Spatie\MediaLibrary\MediaCollections\Models\Media::class);

        config()->set('typescriptable.routes.filename', 'types-routes.d.ts');
        config()->set('typescriptable.routes.filename_list', 'routes.ts');
        config()->set('typescriptable.routes.print_list', true);
        config()->set('typescriptable.routes.add_to_window', false);
        config()->set('typescriptable.routes.use_path', false);

        config()->set('typescriptable.output_path', outputDir());

        config()->set('typescriptable.eloquent.directory', getModelsPath());
        config()->set('typescriptable.eloquent.php_path', outputDir('php'));
        config()->set('typescriptable.eloquent.paginate', true);

        config()->set('typescriptable.engine.eloquent', 'artisan');
    }

    public static function setupDatabase(?string $type = null): void
    {
        if (! $type) {
            return;
        }

        $driver = self::getDriver($type);

        $configs = [
            'sqlite' => [
                'prefix' => $driver->prefix,
            ],
            'mysql' => [
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => $driver->prefix,
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
            'mariadb' => [
                'unix_socket' => '',
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => $driver->prefix,
                'prefix_indexes' => true,
                'strict' => true,
                'engine' => null,
                'options' => extension_loaded('pdo_mysql') ? array_filter([
                    PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
                ]) : [],
            ],
            'pgsql' => [
                'charset' => 'utf8',
                'prefix' => $driver->prefix,
                'prefix_indexes' => true,
                'search_path' => 'public',
                'sslmode' => 'prefer',
            ],
            'sqlsrv' => [
                'charset' => 'utf8',
                'prefix' => $driver->prefix,
                'prefix_indexes' => true,
                // 'encrypt' => env('DB_ENCRYPT', 'yes'),
                // 'trust_server_certificate' => env('DB_TRUST_SERVER_CERTIFICATE', 'false'),
            ],
            'mongodb' => [
                'dsn' => $driver->url,
            ],
        ];

        config()->set('database.default', $driver->name);
        config()->set("database.connections.{$driver->name}", [
            'driver' => $driver->name,
            'url' => $driver->url,
            'host' => $driver->host,
            'port' => $driver->port,
            'database' => $driver->database,
            'username' => $driver->user,
            'password' => $driver->password,
            'prefix' => $driver->prefix,
            ...$configs[$driver->name],
        ]);

        // for github action
        if ($driver->name === 'sqlsrv') {
            $pdo = new PDO("sqlsrv:Server=$driver->host,$driver->port", $driver->user, $driver->password);
            $database = $driver->database;
            // check if database exists
            $stmt = $pdo->query("SELECT * FROM sys.databases WHERE name='$database'");
            if ($stmt->fetch()) {
                $pdo->exec("ALTER DATABASE $database SET SINGLE_USER WITH ROLLBACK IMMEDIATE");
                $pdo->exec("DROP DATABASE $database");
            }
            $pdo->exec("CREATE DATABASE $database");
        } else {
            Schema::dropIfExists($driver->database);
            Schema::dropAllTables($driver->database);
        }

        $migration = include __DIR__.'/Data/database/migrations/create_models_tables.php';
        $migration->up();
    }
}
