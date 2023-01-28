<?php

namespace Kiwilan\Typeable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typeable\Services\TypeableService;

class TypeableCommand extends Command
{
    public $signature = 'typeable:models';

    public $description = 'My command';

    public function handle(): int
    {
        $service = TypeableService::make();

        $namespaces = [];

        foreach ($service->typeables as $typeable) {
            $namespace = "{$typeable->namespace}\\{$typeable->name}";
            $namespaces[] = [$namespace];
        }
        $this->table(['Models'], $namespaces);

        $this->info('Generated model types.');

        $this->comment('All done');

        return self::SUCCESS;
    }
}
