<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableModelsCommand extends Command
{
    public $signature = 'typescriptable:models
                        {--M|models-path : Path to models directory}
                        {--O|output-path : Path to output}
                        {--P|php-path : Path to output PHP classes, if null will not print PHP classes}
                        {--l|legacy : Use legacy mode}';

    public $description = 'Generate Models types.';

    public function handle(): int
    {
        $modelsPath = (string) $this->option('models-path');
        $outputPath = (string) $this->option('output-path');
        $phpPath = (string) $this->option('php-path');
        $legacy = (bool) $this->option('legacy');

        $service = Typescriptable::models(new EloquentConfig(
            modelsPath: $modelsPath,
            outputPath: $outputPath,
            phpPath: $phpPath,
            legacy: $legacy,
        ));
        $namespaces = [];

        foreach ($service->app()->models() as $model) {
            $namespaces[] = [$model->schemaClass()->namespace()];
        }
        $this->table(['Models'], $namespaces);

        $this->info('Generated model types.');

        return self::SUCCESS;
    }
}
