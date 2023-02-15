<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes';

    public $description = 'Generate Routes types.';

    public function handle(): int
    {
        TypescriptableService::route();

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
