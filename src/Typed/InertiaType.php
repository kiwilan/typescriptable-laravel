<?php

namespace Kiwilan\Typescriptable\Typed;

class InertiaType
{
    protected function __construct(
    ) {
    }

    public static function make(): self
    {
        $self = new self();

        return $self;
    }
}
