<?php

namespace Kiwilan\Typescriptable\Typed\Database;

interface IColumn
{
    public static function make(array|object $data, string $table, string $driver): Column;

    public static function typeToPhp(string $formatType): string;
}
