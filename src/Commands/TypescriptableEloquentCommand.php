<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableEloquentCommand extends Command
{
    public $signature = 'typescriptable:eloquent
                        {--M|models-path : Path to Eloquent models directory}
                        {--O|output-path : Path to output}
                        {--P|php-path : Path to output PHP classes, if null will not print PHP classes}
                        {--A|parser : Use parser engine}';

    public $description = 'Generate Eloquent models types.';

    protected function configure()
    {
        $this->setAliases([
            'typescriptable:models',
        ]);

        parent::configure();
    }

    public function handle(): int
    {
        $modelsPath = (string) $this->option('models-path');
        $outputPath = (string) $this->option('output-path');
        $phpPath = (string) $this->option('php-path');
        $parser = (bool) $this->option('parser');

        $service = Typescriptable::models(new EloquentConfig(
            modelsPath: $modelsPath,
            outputPath: $outputPath,
            phpPath: $phpPath,
            useParser: $parser,
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
