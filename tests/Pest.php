<?php

use Kiwilan\Typescriptable\Tests\TestCase;

define('DATABASE_TYPES', [
    'mysql',
    'pgsql',
    'sqlite',
    'sqlsrv',
]);

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
