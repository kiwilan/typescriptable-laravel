<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableZiggyCommand extends Command
{
    public $signature = 'typescriptable:ziggy
                        {--O|output= : Output path for Typescript file, default is `resources/js`.}
                        {--F|output-file= : Output name for Ziggy Typescript file, default is `types-ziggy.d.ts`.}';

    public $description = 'Generate Ziggy types.';

    public function __construct(
        public Typescriptable $typescriptable,
    ) {
        parent::__construct();
        $this->typescriptable = new Typescriptable();
    }

    public function handle(): int
    {
        $this->typescriptable = Typescriptable::make($this, outputFile: 'types-ziggy.d.ts');

        $converter = TypescriptableService::ziggy($this);

        $this->info('Generated Ziggy types.');

        return self::SUCCESS;
    }
}
