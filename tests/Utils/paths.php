<?php

const SETTINGS_EXTENDS = 'Kiwilan\Typescriptable\Tests\Data\Settings\Settings';

/**
 * Get the path to the output directory.
 *
 * Path: `laravel-typescriptable/tests/output`
 *
 * Can be used to get the path to a specific file in the output directory.
 *
 * Path: `laravel-typescriptable/tests/output/{file}`
 */
function pathOutput(?string $file = null): string
{
    $cwd = getcwd();

    if ($file) {
        return "{$cwd}/tests/output/{$file}";
    }

    return "{$cwd}/tests/output";
}

/**
 * Get the path to the data directory.
 *
 * Path: `laravel-typescriptable/tests/Data`
 */
function pathData(?string $file = null): string
{
    $cwd = getcwd();

    if ($file) {
        return "{$cwd}/tests/Data/{$file}";
    }

    return "{$cwd}/tests/Data";
}

/**
 * Get the path to the models directory.
 *
 * Path: `laravel-typescriptable/tests/Data/Models`
 */
function pathModels(): string
{
    return pathData('Models');
}

/**
 * Get the path to a specific model file.
 *
 * Path: `laravel-typescriptable/tests/Data/Models/{model}`
 */
function pathModel(string $model, string $extension = 'php'): string
{
    $model = str_replace('\\', '/', $model);
    $model = str_replace('App/Models/', '', $model);

    return pathModels("{$model}.{$extension}");
}

/**
 * Get `SplFileInfo` for a specific model file.
 */
function getModelSpl(string $file): SplFileInfo
{
    return new SplFileInfo(pathModel($file));
}

/**
 * Get the path to the PHP output directory.
 *
 * Path: `laravel-typescriptable/tests/output`
 */
function pathPhp(): string
{
    return pathOutput();
}

/**
 * Get the path to the routes JSON file output.
 */
function pathRoutesJson(): string
{
    return pathData('routes.json');
}

/**
 * Get the path to the settings directory (for `spatie/laravel-settings`).
 */
function pathSettings(): string
{
    return pathData('Settings');
}

/**
 * Get the path for the artisan command output.
 */
function pathArtisan(string $file): string
{
    return pathData("ModelsJson/{$file}.json");
}
