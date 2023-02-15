<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableInertiaCommand extends Command
{
    public $signature = 'typescriptable:inertia';

    public $description = 'Generate Inertia types.';

    public function handle(): int
    {
        TypescriptableService::inertia();

        $this->info('Generated Inertia types.');

        return self::SUCCESS;
    }
}
