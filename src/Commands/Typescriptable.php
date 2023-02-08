<?php

namespace Kiwilan\Typescriptable\Commands;

use Illuminate\Console\Command;

class Typescriptable
{
    public function __construct(
        public string $outputPath = 'resources/js',
        public string $outputFile = 'types-models.d.ts',
    ) {
    }

    public static function make(Command $command, string $outputPath = 'resources/js', string $outputFile = 'types-models.d.ts'): Typescriptable
    {
        $item = new Typescriptable();

        $outputPath = $command->option('output') ?? $outputPath;
        $item->outputPath = base_path($outputPath);
        $item->outputFile = $command->option('output-file') ?? $outputFile;

        return $item;
    }
}
