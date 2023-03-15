<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\RouteType;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes';

    public $description = 'Generate Routes types.';

    public function handle(): int
    {
        RouteType::make();

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
