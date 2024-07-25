<?php

use Dotenv\Dotenv;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\TypescriptableConfig;

foreach (glob('.output/*') as $file) {
    if (basename($file) !== '.gitignore') {
        if (is_dir($file)) {
            rmdir($file);
        } else {
            unlink($file);
        }
    }
}

function DatabaseDriverEnums(): array
{
    $dotenv = Dotenv::createMutable(getcwd());
    $data = $dotenv->load();
    $types = $data['DATABASE_TYPES'] ?? 'mysql,mariadb,sqlite,pgsql,sqlsrv';
    $types = explode(',', $types);

    return $types;
}

function DatabaseDriverEnumsWithoutSqlsrv(): array
{
    $drivers = DatabaseDriverEnums();
    if (($key = array_search('sqlsrv', $drivers)) !== false) {
        unset($drivers[$key]);
    }

    return $drivers;
}

function outputDir(?string $file = null): string
{
    $currentDir = getcwd();

    if ($file) {
        return "{$currentDir}/tests/output/{$file}";
    }

    return "{$currentDir}/tests/output";
}

function models(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/Models";
}

function eloquentConfig(string $eloquentEngine = 'artisan'): void
{
    deleteFile(outputDir(TypescriptableConfig::eloquentFilename()));
    deleteDir(outputDir(TypescriptableConfig::eloquentPhpPath()));

    config()->set('typescriptable.output_path', outputDir());
    config()->set('typescriptable.engine.eloquent', $eloquentEngine);
    config()->set('typescriptable.eloquent.directory', models());
    config()->set('typescriptable.eloquent.php_path', outputDir('php'));
    config()->set('typescriptable.eloquent.paginate', true);
    config()->set('typescriptable.eloquent.skip', [
        'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
    ]);
}

function routes(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/routes.json";
}

function settings(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/Settings";
}

function deleteFile(string $file): void
{
    if (file_exists($file)) {
        unlink($file);
    }
}

function deleteDir(string $dir): void
{
    // if (! is_dir($dir)) {
    //     return;
    // }

    // foreach (scandir($dir) as $file) {

    //     if (is_dir("$dir/$file")) {
    //         deleteDir("$dir/$file");
    //     } else {
    //         unlink("$dir/$file");
    //     }
    // }
    // rmdir($dir);

    $files = glob("{$dir}/*");
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        if ($file === '.gitignore') {
            continue;
        }
        if (is_file($file)) {
            unlink($file);
        } elseif (is_dir($file)) {
            deleteDir($file);
            rmdir($file);
        }
    }
}

function settingsDir(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/Settings";
}

function setttingsOutputDir(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/output";
}

function settingsExtends(): string
{
    return 'Kiwilan\Typescriptable\Tests\Data\Settings\Settings';
}

uses(TestCase::class)->in(__DIR__);
