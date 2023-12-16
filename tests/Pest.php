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

function getDatabaseTypes(): array
{
    $dotenv = Dotenv::createMutable(getcwd());
    $data = $dotenv->load();
    $types = $data['DATABASE_TYPES'] ?? 'mysql,sqlite,pgsql,sqlsrv';
    $types = explode(',', $types);

    return $types;
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

uses(TestCase::class)->in(__DIR__);
