<?php

namespace Kiwilan\TypeableModels\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Kiwilan\TypeableModels\TypeableModels
 */
class TypeableModels extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Kiwilan\TypeableModels\TypeableModels::class;
    }
}
