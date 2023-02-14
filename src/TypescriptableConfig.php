<?php

namespace Kiwilan\Typescriptable;

class TypescriptableConfig
{
    public static function outputPath(): string
    {
        return  config('typescriptable.output_path') ?? resource_path('js');
    }

    public static function filenameModels(): string
    {
        return  config('typescriptable.filename.models') ?? 'types-models.d.ts';
    }

    public static function filenameRoutes(): string
    {
        return  config('typescriptable.filename.routes') ?? 'types-routes.d.ts';
    }

    public static function filenameZiggy(): string
    {
        return  config('typescriptable.filename.ziggy') ?? 'types-ziggy.d.ts';
    }
}
