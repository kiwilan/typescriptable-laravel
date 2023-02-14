<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Services\TypescriptableService;

class TypescriptableZiggyCommand extends Command
{
    public $signature = 'typescriptable:ziggy
                        {--skip-router : Skip generation of Laravel routes with `ZiggyLaravelRoutes` interface.}
                        {--skip-page : Skip generation of `skipPage` interface with `InertiaPage` interface.}
                        {--E|embed : For Vue plugin `InertiaTyped` only, generate global methods interface (default is `false`). Require `ziggy-js` package.}';

    public $description = 'Generate Ziggy types.';

    public function __construct(
        public bool $skipRouter = true,
        public bool $skipPage = true,
        public bool $useEmbed = false,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->skipRouter = $this->option('skip-router') ?? true;
        $this->skipPage = $this->option('skip-page') ?? true;
        $this->useEmbed = $this->option('embed') ?? false;

        TypescriptableService::ziggy($this);

        $this->info('Generated Ziggy types.');

        return self::SUCCESS;
    }
}
