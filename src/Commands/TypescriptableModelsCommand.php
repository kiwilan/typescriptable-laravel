<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableModelsCommand extends Command
{
    public $signature = 'typescriptable:models
                        {--M|models-path : Path to models directory}
                        {--O|output-path : Path to output}
                        {--P|php-path : Path to output PHP classes, if null will not print PHP classes}';

    public $description = 'Generate Models types.';

    public function __construct(
        public ?string $modelsPath = null,
        public ?string $outputPath = null,
        public ?string $phpPath = null,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->modelsPath = (string) $this->option('models-path');
        $this->outputPath = (string) $this->option('output-path');
        $this->phpPath = (string) $this->option('php-path');

        $service = Typescriptable::models($this->modelsPath, $this->outputPath, $this->phpPath);
        $namespaces = [];

        foreach ($service->items() as $item) {
            $namespaces[] = [$item->namespace];
        }
        $this->table(['Models'], $namespaces);

        $this->info('Generated model types.');

        return self::SUCCESS;
    }
}
