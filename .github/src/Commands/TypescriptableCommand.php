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

    public function handle(): int
    {
        $this->newLine();
        $this->info('Generating types...');

        $eloquent = $this->option('eloquent') ?: false;
        $routes = $this->option('routes') ?: false;
        $settings = $this->option('settings') ?: false;

        if (! $eloquent && ! $routes && ! $settings) {
            $eloquent = true;
            $routes = true;
            $settings = true;
        }

        if ($eloquent) {
            $this->info('Generating types for Eloquent...');
            Artisan::call('typescriptable:eloquent', [
            ], $this->output);
        }

        if ($routes) {
            $this->info('Generating types for Routes...');
            Artisan::call('typescriptable:routes', [
            ], $this->output);
        }

        if ($settings) {
            $this->info('Generating types for Settings...');
            Artisan::call('typescriptable:settings', [
            ], $this->output);
        }

        $this->info('Generated types.');

        return self::SUCCESS;
    }
}
