<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;
use Kiwilan\Typescriptable\Utils\TypeOption;

class TypescriptableRoutesCommand extends Command
{
    public $signature = 'typescriptable:routes';

    public $description = 'Generate Routes types.';

    public function __construct(
        public TypeOption $opts,
        public bool $skipRouter = true,
        public bool $skipPage = true,
        public bool $useEmbed = false,
    ) {
        parent::__construct();
        $this->opts = new TypeOption();
    }

    public function handle(): int
    {
        // $this->typescriptable = Typescriptable::make($this, outputFile: 'types-ziggy.d.ts');
        // $this->skipRouter = $this->option('skip-router') ?? true;
        // $this->skipPage = $this->option('skip-page') ?? true;
        // $this->useEmbed = $this->option('embed') ?? false;

        TypescriptableService::route($this);

        $this->info('Generated Routes types.');

        return self::SUCCESS;
    }
}
