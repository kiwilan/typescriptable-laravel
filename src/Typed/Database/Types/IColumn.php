<?php

namespace Kiwilan\Typescriptable\Typed\Database\Types;

use Kiwilan\Typescriptable\Typed\Eloquent\Schema\Model\SchemaModelAttribute;

interface IColumn
{
    public static function make(array|object $data): SchemaModelAttribute;
}
