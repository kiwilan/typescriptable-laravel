<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typed\Route\RouteConfig;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes
                        {--o|output-path : Path to output TS file}
                        {--l|list : Print a TS file with all routes as object}';

    public $description = 'Generate Routes types.';

    public function handle(): int
    {
        $outputPath = (string) $this->option('output-path');
        $withList = (bool) $this->option('list');

        Typescriptable::routes(new RouteConfig(
            outputPath: $outputPath,
            withList: $withList,
        ));

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
