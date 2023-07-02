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

    public function __construct(
        public ?string $settingsPath = null,
        public ?string $outputPath = null,
        public ?string $extends = null,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->settingsPath = (string) $this->option('settings-path');
        $this->outputPath = (string) $this->option('output-path');
        $this->extends = (string) $this->option('extends');

        $service = Typescriptable::settings($this->settingsPath, $this->outputPath, $this->extends);
        // $namespaces = [];

        // foreach ($service->items as $item) {
        //     $namespaces[] = [$item->namespace];
        // }
        // $this->table(['Models'], $namespaces);

        $this->info('Generated settings types.');

        return self::SUCCESS;
    }
}
