<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableCommand extends Command
{
    public $signature = 'typescriptable:models
                        {--T|fake-team : For Jetstream, add fake Team model if you choose to not install teams to prevent errors in components.}
                        {--M|models-path= : The path to the models.}
                        {--O|output= : Output path for Typescript file.}
                        {--F|output-file= : Output name for Typescript file.}
                        {--P|paginate : Add paginate type for Laravel pagination.}';

    public $description = 'Generate model types.';

    public function __construct(
        public bool $fakeTeam = false,
        public string $modelsPath = 'app/Models',
        public string $outputPath = 'resources/js',
        public string $outputFile = 'types-models.d.ts',
        public bool $paginate = false,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->fakeTeam = $this->option('fake-team') ?? false;
        $this->modelsPath = $this->option('models-path') ?? 'app/Models';
        $this->outputPath = $this->option('output') ?? 'resources/js';
        $this->outputFile = $this->option('output-file') ?? 'types-models.d.ts';
        $this->paginate = $this->option('paginate') ?? false;

        $this->modelsPath = base_path($this->modelsPath);
        $this->outputPath = base_path($this->outputPath);

        $service = TypescriptableService::make($this);
        $namespaces = [];

        foreach ($service->typeables as $typescriptable) {
            $namespace = "{$typescriptable->namespace}\\{$typescriptable->name}";
            $namespaces[] = [$namespace];
        }
        $this->table(['Models'], $namespaces);

        $this->info('Generated model types.');

        $this->comment('All done');

        return self::SUCCESS;
    }
}
