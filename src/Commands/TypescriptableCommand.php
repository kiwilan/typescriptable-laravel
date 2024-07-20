<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class TypescriptableCommand extends Command
{
    public $signature = 'typescriptable
                        {--E|eloquent : Generate Eloquent models types.}
                        {--R|routes : Generate Routes types.}
                        {--S|settings : Generate Settings types.}';

    public $description = 'Generate types.';

    public function __construct(
        public bool $eloquent = false,
        public bool $routes = false,
        public bool $settings = false,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->newLine();
        $this->info('Generating types...');

        $this->eloquent = $this->option('eloquent') ?: false;
        $this->routes = $this->option('routes') ?: false;
        $this->settings = $this->option('settings') ?: false;

        if (! $this->eloquent && ! $this->routes && ! $this->settings) {
            $this->eloquent = true;
            $this->routes = true;
            $this->settings = true;
        }

        if ($this->eloquent) {
            $this->info('Generating types for Eloquent...');
            Artisan::call('typescriptable:eloquent', [
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
