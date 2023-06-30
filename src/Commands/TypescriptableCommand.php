<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TypescriptableCommand extends Command
{
    public $signature = 'typescriptable
                        {--M|models : Generate Models types.}
                        {--R|routes : Generate Routes types.}
                        {--S|settings : Generate Settings types.}';

    public $description = 'Generate types.';

    public function __construct(
        public bool $models = false,
        public bool $routes = false,
        public bool $settings = false,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->newLine();
        $this->info('Generating types...');

        $this->models = $this->option('models') ?: false;
        $this->routes = $this->option('routes') ?: false;
        $this->settings = $this->option('settings') ?: false;

        if (! $this->models && ! $this->routes && ! $this->settings) {
            $this->models = true;
            $this->routes = true;
            $this->settings = true;
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

        if ($this->settings) {
            $this->info('Generating types for Settings...');
            Artisan::call('typescriptable:settings', [
            ], $this->output);
        }

        $this->info('Generated types.');

        return self::SUCCESS;
    }
}
