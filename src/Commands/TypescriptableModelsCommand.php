<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Utils\TypeOption;

class TypescriptableModelsCommand extends Command
{
    public $signature = 'typescriptable:models
                        {--O|output= : Output path for Typescript file, default is `resources/js`.}
                        {--F|output-file= : Output name for Typescript file, default is `types-models.d.ts`.}
                        {--M|models-path= : The path to the models.}
                        {--P|paginate : Add paginate type for Laravel pagination.}
                        {--T|fake-team : For Jetstream, add fake Team model if you choose to not install teams to prevent errors in components.}';

    public $description = 'Generate model types.';

    public function __construct(
        public TypeOption $opts,
        public string $modelsPath = 'app/Models',
        public bool $paginate = false,
        public bool $fakeTeam = false,
    ) {
        parent::__construct();
        $this->opts = new TypeOption();
    }

    public function handle(): int
    {
        $this->fakeTeam = $this->option('fake-team') ?? false;
        $this->paginate = $this->option('paginate') ?? false;

        $modelsPath = $this->option('models-path') ?? 'app/Models';
        $this->modelsPath = base_path($modelsPath);

        $this->opts = TypeOption::make($this);

        $service = TypescriptableService::models($this);
        $namespaces = [];

        foreach ($service->typeables as $typescriptable) {
            $namespace = "{$typescriptable->namespace}\\{$typescriptable->name}";
            $namespaces[] = [$namespace];
        }
        $this->table(['Models'], $namespaces);

        $this->info('Generated model types.');

        return self::SUCCESS;
    }
}
