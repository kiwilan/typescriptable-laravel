<?php

namespace Kiwilan\Typescriptable\Services;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Commands\TypescriptableCommand;
use Kiwilan\Typescriptable\Services\TypescriptableService\TypescriptableClass;
use Kiwilan\Typescriptable\Services\TypescriptableService\Utils\TypescriptableTeam;

/**
 * @property string $path
 * @property TypescriptableClass[] $typeables
 */
class TypescriptableService
{
    protected function __construct(
        public string $path,
        public TypescriptableCommand $command,
        /** @var TypescriptableClass[] */
        public array $typeables = [],
    ) {
    }

    public static function make(TypescriptableCommand $command): self
    {
        $path = $command->modelsPath;

        $service = new TypescriptableService($path, $command);
        $service->typeables = $service->setTypescriptables();

        if ($service->command->fakeTeam) {
            $service->typeables['Team'] = TypescriptableClass::fake('Team', TypescriptableTeam::setFakeTeam());
        }

        $service->setTsModelTypes();
        // $service->setPhpModelTypes();

        return $service;
    }

    protected function setPhpModelTypes()
    {
        foreach ($this->typeables as $name => $typescriptable) {
            unset($typescriptable->reflector);
            $path = app_path('Types');

            if (! File::exists($path)) {
                File::makeDirectory($path);
            }
            $filename = "{$name}.php";
            $path = "{$path}/{$filename}";
            File::put($path, $typescriptable->typeableModel->phpString);
        }
    }

    protected function setTsModelTypes()
    {
        $content = [];

        $content[] = '// This file is auto generated by GenerateTypeCommand.';
        $content[] = 'declare namespace App.Models {';

        foreach ($this->typeables as $typescriptable) {
            $content[] = $typescriptable->typeableModel?->tsString;
        }
        $content[] = '}';

        $content = implode(PHP_EOL, $content);

        $path = $this->command->outputPath;
        $filename = $this->command->outputFile;

        $path = "{$path}/{$filename}";
        File::put($path, $content);
    }

    /**
     * @return TypescriptableClass[]
     */
    protected function setTypescriptables(): array
    {
        $classes = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->path, \FilesystemIterator::SKIP_DOTS)
        );

        /** @var \SplFileInfo $file */
        foreach ($iterator as $file) {
            if (! $file->isDir()) {
                $model = TypescriptableClass::make(
                    path: $file->getPathname(),
                    file: $file,
                    command: $this->command
                );
                $classes[$model->name] = $model;
            }
        }

        return $classes;
    }
}