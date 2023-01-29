<?php

namespace Kiwilan\Typescriptable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kiwilan\Typescriptable\Typescriptable
 */
class Typescriptable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Kiwilan\Typescriptable\Typescriptable::class;
    }
}
