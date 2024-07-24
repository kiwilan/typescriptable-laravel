<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableSettingsCommand extends Command
{
    public $signature = 'typescriptable:settings';

    public $description = 'Generate Spatie Settings types.';

    public function handle(): int
    {
        Typescriptable::settings();

        $this->info('Generated settings types.');

        return self::SUCCESS;
    }
}
