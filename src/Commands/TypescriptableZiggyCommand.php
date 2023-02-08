<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableZiggyCommand extends Command
{
    public $signature = 'typescriptable:ziggy
                        {--O|output= : Output path for Typescript file, default is `resources/js`.}
                        {--F|output-file= : Output name for Ziggy Typescript file, default is `types-ziggy.d.ts`.}
                        {--skip-router : Skip generation of Laravel routes with `ZiggyLaravelRoutes` interface.}
                        {--skip-page : Skip generation of `usePage` interface with `InertiaPage` interface.}
                        {--E|embed : For Vue plugin `InertiaTyped` only, generate global methods interface (default is `false`). Require `ziggy-js` package.}';

    public $description = 'Generate Ziggy types.';

    public function __construct(
        public Typescriptable $typescriptable,
        public bool $useRouter = true,
        public bool $usePage = true,
        public bool $useEmbed = false,
    ) {
        parent::__construct();
        $this->typescriptable = new Typescriptable();
    }

    public function handle(): int
    {
        $this->typescriptable = Typescriptable::make($this, outputFile: 'types-ziggy.d.ts');
        $this->useRouter = $this->option('router') ?? true;
        $this->usePage = $this->option('page') ?? true;
        $this->useEmbed = $this->option('embed') ?? false;

        $converter = TypescriptableService::ziggy($this);

        $this->info('Generated Ziggy types.');

        return self::SUCCESS;
    }
}
