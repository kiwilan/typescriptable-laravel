<?php

namespace Kiwilan\Typescriptable\Services\Types;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Commands\TypescriptableZiggyCommand;
use Kiwilan\Typescriptable\Services\Types\Ziggy\InertiaEmbed;
use Kiwilan\Typescriptable\Services\Types\Ziggy\InertiaPage;
use Kiwilan\Typescriptable\TypescriptableConfig;

class ZiggyType
{
    protected function __construct(
      public string $filePath,
      public bool $skipRouter = true,
      public bool $skipPage = true,
      public bool $useEmbed = false,
    ) {
    }

    public static function make(TypescriptableZiggyCommand $command): self
    {
        $path = TypescriptableConfig::outputPath();
        $filename = TypescriptableConfig::filenameZiggy();

        $file = "{$path}/{$filename}";
        $service = new self(
            filePath: $file,
            skipRouter: $command->skipRouter,
            skipPage: $command->skipPage,
            useEmbed: $command->useEmbed,
        );
        $generatedRoutes = $service->generate();

        if (! File::isDirectory($path)) {
            File::makeDirectory($filename);
        }
        File::put($file, $generatedRoutes);

        return $service;
    }

    private function generate(): string
    {
        $skipPage = $this->skipPage ? '' : InertiaPage::make();
        $useEmbed = $this->useEmbed ? InertiaEmbed::make() : InertiaEmbed::native();

        $routerInterface = $this->skipRouter ? '' : 'declare interface ZiggyLaravelRoutes extends LaravelRoutes {}';
        $pageInterface = $this->skipPage ? '' : 'declare interface InertiaPage extends IPage {}';

        $export = $this->skipRouter ? '' : 'export { LaravelRoutes };';

        return <<<typescript
// This file is auto generated by TypescriptableLaravel.
{$skipPage}

declare global {
  {$routerInterface}
  {$pageInterface}
}

{$useEmbed}

{$export}

typescript;
    }
}
