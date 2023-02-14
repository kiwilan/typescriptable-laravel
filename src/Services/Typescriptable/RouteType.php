<?php

namespace Kiwilan\Typescriptable\Services\Typescriptable;

use Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand;

class RouteType
{
    protected function __construct(
        protected TypescriptableRoutesCommand $command,
    ) {
    }

    public static function make(TypescriptableRoutesCommand $command): self
    {
        $route = new self($command);

        return $route;
    }
}
