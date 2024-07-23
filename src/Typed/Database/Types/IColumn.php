<?php

namespace Kiwilan\Typescriptable\Typed\Database\Types;

use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModelAttribute;

interface IColumn
{
    public static function make(array|object $data): SchemaModelAttribute;
}
