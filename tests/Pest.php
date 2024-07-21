<?php

use Dotenv\Dotenv;
use Kiwilan\Typescriptable\Tests\TestCase;

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
    if (! is_dir($dir)) {
        return;
    }

    foreach (scandir($dir) as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        if (is_dir("$dir/$file")) {
            deleteDir("$dir/$file");
        } else {
            unlink("$dir/$file");
        }
    }
    rmdir($dir);
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
