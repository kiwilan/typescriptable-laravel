<?php

namespace Kiwilan\Typescriptable\Eloquent\Utils;

use Illuminate\Support\Facades\File;

class TypescriptableUtils
{
    public static function print(string $contents, string $path): void
    {
        if (! File::exists(dirname($path))) {
            File::makeDirectory(dirname($path));
        }
        File::delete($path);

        File::put($path, $contents);
    }
}
