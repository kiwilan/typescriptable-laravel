<?php

namespace Kiwilan\Typeable\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kiwilan\Typeable\Typeable
 */
class Typeable extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Kiwilan\Typeable\Typeable::class;
    }
}
