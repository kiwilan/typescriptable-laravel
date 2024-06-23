<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Typescriptable;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes
                        {--j|json : Path to JSON route list}
                        {--l|list : Print a TS file with all routes as object}
                        {--o|output-path : Path to output TS file}';

    public $description = 'Generate Routes types.';

    public function __construct(
        public ?string $json_output = null,
        public ?bool $with_list = false,
        public ?string $output_path = null,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->json_output = (string) $this->option('json');
        $this->with_list = (bool) $this->option('list');
        $this->output_path = (string) $this->option('output-path');

        Typescriptable::routes($this->json_output, $this->with_list, $this->output_path);

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
