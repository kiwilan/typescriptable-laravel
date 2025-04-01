<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes';

    public $description = 'Generate Routes types.';

    public function handle(): int
    {
        Typescriptable::routes();

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
