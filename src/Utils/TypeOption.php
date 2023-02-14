<?php

namespace Kiwilan\Typescriptable\Utils;

use Illuminate\Console\Command;

class TypeOption
{
    public function __construct(
        public string $outputPath = 'resources/js',
        public string $outputFile = 'types-models.d.ts',
    ) {
    }

    public static function make(Command $command, string $outputPath = 'resources/js', string $outputFile = 'types-models.d.ts'): TypeOption
    {
        $item = new TypeOption();

        $outputPath = $command->option('output') ?? $outputPath;
        $item->outputPath = base_path($outputPath);
        $item->outputFile = $command->option('output-file') ?? $outputFile;

        return $item;
    }
}
