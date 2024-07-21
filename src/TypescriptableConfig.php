<?php

namespace Kiwilan\Typescriptable;

use Illuminate\Support\Facades\File;

class TypescriptableConfig
{
    public static function engineModels(): string
    {
        $engine = config('typescriptable.engine.models') ?? 'artisan';

        if (! in_array($engine, ['artisan', 'parser'])) {
            throw new \Exception('Invalid engine models');
        }

        return $engine;
    }

    public static function outputPath(): string
    {
        $path = config('typescriptable.output_path') ?? resource_path('js');

        if (! File::isDirectory($path)) {
            File::makeDirectory($path);
        }

        return $path;
    }

    public static function setPath(?string $filename = null): string
    {
        if (! $filename) {
            return TypescriptableConfig::outputPath();
        }

        return TypescriptableConfig::outputPath().DIRECTORY_SEPARATOR.$filename;
    }

    public static function eloquentFilename(): string
    {
        return config('typescriptable.eloquent.filename') ?? 'types-eloquent.d.ts';
    }

    public static function eloquentDirectory(): string
    {
        return config('typescriptable.eloquent.directory') ?? app_path('Models');
    }

    public static function eloquentPhpPath(): ?string
    {
        return config('typescriptable.eloquent.php_path') ?? null;
    }

    /**
     * Eloquent models to skip.
     *
     * @return string[]
     */
    public static function eloquentSkip(): array
    {
        return config('typescriptable.eloquent.skip') ?? [];
    }

    public static function eloquentPaginate(): bool
    {
        return config('typescriptable.eloquent.paginate') ?? true;
    }

    public static function eloquentFakeTeam(): bool
    {
        return config('typescriptable.eloquent.fake_team') ?? false;
    }

    public static function settingsFilename(): string
    {
        return config('typescriptable.settings.filename') ?? 'types-settings.d.ts';
    }

    public static function settingsDirectory(): string
    {
        return config('typescriptable.settings.directory') ?? app_path('Settings');
    }

    public static function settingsSkip(): array
    {
        return config('typescriptable.settings.skip') ?? [];
    }

    public static function routesFilename(): string
    {
        return config('typescriptable.routes.filename') ?? 'types-routes.d.ts';
    }

    public static function routesFilenameList(): string
    {
        return config('typescriptable.routes.filename_list') ?? 'routes.ts';
    }

    public static function routesUsePath(): bool
    {
        return config('typescriptable.routes.use_path') ?? false;
    }

    public static function routesSkipName(): array
    {
        return config('typescriptable.routes.skip.name') ?? [
            '__clockwork.*',
            'debugbar.*',
            'horizon.*',
            'telescope.*',
            'nova.*',
            'lighthouse.*',
            'livewire.*',
            'ignition.*',
            'filament.*',
            'log-viewer.*',
        ];
    }

    public static function routesSkipPath(): array
    {
        return config('typescriptable.routes.skip.path') ?? [
            'api/*',
        ];
    }

    public static function inertiaFilename(): string
    {
        return config('typescriptable.inertia.filename') ?? 'types-inertia.d.ts';
    }

    public static function inertiaFilenameGlobal(): string
    {
        return config('typescriptable.inertia.filename_global') ?? 'types-inertia-global.d.ts';
    }

    public static function inertiaGlobal(): bool
    {
        return config('typescriptable.inertia.global') ?? true;
    }

    public static function inertiaPage(): bool
    {
        return config('typescriptable.inertia.page') ?? true;
    }

    public static function inertiaNpmTypescriptableLaravel(): bool
    {
        return config('typescriptable.inertia.npm_typescriptable_laravel') ?? false;
    }
}
