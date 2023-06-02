<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\RouteType;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes
                        {--route-list : Path to JSON route list}
                        {--output-path : Path to output}';

    public $description = 'Generate Routes types.';

    public function __construct(
        public ?string $routeList = null,
        public ?string $outputPath = null,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->routeList = (string) $this->option('route-list');
        $this->outputPath = (string) $this->option('output-path');

        RouteType::make($this->routeList, $this->outputPath);

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
