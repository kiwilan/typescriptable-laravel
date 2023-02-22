<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TypescriptableCommand extends Command
{
    public $signature = 'typescriptable
                        {--A|all : Generate all types.}
                        {--M|models : Generate Models types.}
                        {--R|routes : Generate Routes types.}';

    public $description = 'Generate types.';

    public function __construct(
        public bool $all = false,
        public bool $models = false,
        public bool $routes = false,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->all = $this->option('all') ?? false;
        $this->models = $this->option('models') ?? false;
        $this->routes = $this->option('routes') ?? false;

        if ($this->all) {
            $this->info('Generating all types...');
            $this->models = true;
            $this->routes = true;
        }

        if ($this->models) {
            $this->info('Generating Models types...');
            Artisan::call('typescriptable:models', [
            ], $this->output);
        }

        if ($this->routes) {
            $this->info('Generating Routes types...');
            Artisan::call('typescriptable:routes', [
            ], $this->output);
        }

        $this->info('Generated types.');

        return self::SUCCESS;
    }
}
