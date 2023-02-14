<?php

namespace Kiwilan\Typescriptable\Services\Types;

use Kiwilan\Typescriptable\Commands\TypescriptableRoutesCommand;

class RouteType
{
    protected function __construct(
        protected TypescriptableRoutesCommand $command,
    ) {
    }

    public static function make(TypescriptableRoutesCommand $command): self
    {
        $path = config('typescriptable.output_path');
        $filename = config('typescriptable.filename.routes');

        $route = new self($command);

        return $route;
    }
}
