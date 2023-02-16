<?php

namespace Kiwilan\Typescriptable;

class TypescriptableConfig
{
    public static function outputPath(): string
    {
        return  config('typescriptable.output_path') ?? 'node_modules/@types';
    }

    public static function modelsFilename(): string
    {
        return  config('typescriptable.models.filename') ?? 'types-models.d.ts';
    }

    public static function modelsDirectory(): string
    {
        return  config('typescriptable.models.directory') ?? app_path('Models');
    }

    public static function modelsSkip(): array
    {
        return  config('typescriptable.models.skip') ?? [];
    }

    public static function modelsPaginate(): bool
    {
        return  config('typescriptable.models.paginate') ?? true;
    }

    public static function modelsFakeTeam(): bool
    {
        return  config('typescriptable.models.fake_team') ?? false;
    }

    public static function routesFilename(): string
    {
        return  config('typescriptable.routes.filename') ?? 'types-routes.d.ts';
    }

    public static function routesFilenameList(): string
    {
        return  config('typescriptable.routes.filename_list') ?? 'routes.ts';
    }

    public static function routesSkipName(): array
    {
        return  config('typescriptable.routes.skip.name') ?? [];
    }

    public static function routesSkipPath(): array
    {
        return  config('typescriptable.routes.skip.path') ?? [];
    }

    public static function inertiaFilename(): string
    {
        return  config('typescriptable.inertia.filename') ?? 'types-inertia.d.ts';
    }

    public static function inertiaFilenameGlobal(): string
    {
        return  config('typescriptable.inertia.filename_global') ?? 'types-inertia-global.d.ts';
    }

    public static function inertiaGlobal(): bool
    {
        return  config('typescriptable.inertia.global') ?? true;
    }

    public static function inertiaPage(): bool
    {
        return  config('typescriptable.inertia.page') ?? true;
    }

    public static function inertiaUseEmbed(): bool
    {
        return  config('typescriptable.inertia.use_embed') ?? false;
    }
}
