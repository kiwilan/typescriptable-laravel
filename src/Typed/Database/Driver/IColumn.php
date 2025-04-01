<?php

namespace Kiwilan\Typescriptable\Typed\Database\Driver;

use Kiwilan\Typescriptable\Typed\Schema\SchemaAttribute;

interface IColumn
{
    public static function make(array|object $data): SchemaAttribute;
}
