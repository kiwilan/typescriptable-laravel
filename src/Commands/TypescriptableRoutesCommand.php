<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes';

    public $description = 'Generate Routes types.';

    public function __construct(
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        TypescriptableService::route($this);

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
