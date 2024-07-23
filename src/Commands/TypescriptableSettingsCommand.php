<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableSettingsCommand extends Command
{
    public $signature = 'typescriptable:settings
                        {--S|settings-path : Path to settings directory}
                        {--O|output-path : Path to output}
                        {--E|extends : Extends class to parse}';

    public $description = 'Generate Spatie Settings types.';

    public function handle(): int
    {
        $settingsPath = (string) $this->option('settings-path');
        $outputPath = (string) $this->option('output-path');
        $extends = (string) $this->option('extends');

        Typescriptable::settings($settingsPath, $outputPath, $extends);

        $this->info('Generated settings types.');

        return self::SUCCESS;
    }
}
