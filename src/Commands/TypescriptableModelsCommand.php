<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableModelsCommand extends Command
{
    public $signature = 'typescriptable:models
                        {--models-path : Path to models directory}
                        {--output-path : Path to output}';

    public $description = 'Generate Models types.';

    public function __construct(
        public ?string $modelsPath = null,
        public ?string $outputPath = null,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->modelsPath = (string) $this->option('models-path');
        $this->outputPath = (string) $this->option('output-path');

        $service = Typescriptable::models($this->modelsPath, $this->outputPath);
        $namespaces = [];

        foreach ($service->items as $item) {
            $namespaces[] = [$item->namespace];
        }
        $this->table(['Models'], $namespaces);

        $this->info('Generated model types.');

        return self::SUCCESS;
    }
}
