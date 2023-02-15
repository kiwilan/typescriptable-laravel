<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableModelsCommand extends Command
{
    public $signature = 'typescriptable:models';

    public $description = 'Generate Models types.';

    public function handle(): int
    {
        $service = TypescriptableService::models();
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
