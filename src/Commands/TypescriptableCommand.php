<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TypescriptableCommand extends Command
{
    public $signature = 'typescriptable
                        {--M|models : Generate Models types.}
                        {--R|routes : Generate Routes types.}';

    public $description = 'Generate types.';

    public function __construct(
        public bool $models = false,
        public bool $routes = false,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->newLine();
        $this->info('Generating types...');

        $this->models = $this->option('models') ?? false;
        $this->routes = $this->option('routes') ?? false;

        if (! $this->models && ! $this->routes) {
            $this->models = true;
            $this->routes = true;
        }

        if ($this->models) {
            $this->info('Generating types for Models...');
            Artisan::call('typescriptable:models', [
            ], $this->output);
        }

        if ($this->routes) {
            $this->info('Generating types for Routes...');
            Artisan::call('typescriptable:routes', [
            ], $this->output);
        }

        $this->info('Generated types.');

        return self::SUCCESS;
    }
}
