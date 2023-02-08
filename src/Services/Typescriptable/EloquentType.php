<?php

namespace Kiwilan\Typescriptable\Services;

use Illuminate\Support\Facades\File;
use Kiwilan\Typescriptable\Commands\TypescriptableModelsCommand;
use Kiwilan\Typescriptable\Services\Typescriptable\Models\ClassTemplate;
use Kiwilan\Typescriptable\Services\Typescriptable\Utils\LaravelPaginateType;
use Kiwilan\Typescriptable\Services\Typescriptable\Utils\LaravelTeamType;

/**
 * @property string $path
 * @property ClassTemplate[] $typeables
 */
class EloquentType
{
    protected function __construct(
        public string $path,
        public TypescriptableModelsCommand $command,
        /** @var ClassTemplate[] */
        public array $typeables = [],
    ) {
    }

    public static function make(TypescriptableModelsCommand $command): self
    {
        $path = $command->modelsPath;

        $service = new EloquentType($path, $command);
        $service->typeables = $service->setTypescriptables();

        if ($service->command->fakeTeam) {
            $service->typeables['Team'] = ClassTemplate::fake('Team', LaravelTeamType::setFakeTeam());
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

        $content[] = '// This file is auto generated by TypescriptableCommand.';
        $content[] = 'declare namespace App {';
        $content[] = '  declare namespace Models {';

        foreach ($this->typeables as $typescriptable) {
            $content[] = "    {$typescriptable->typeableModel->tsString}";
        }
        $content[] = '  }';

        if ($this->command->paginate) {
            $content[] = LaravelPaginateType::make();
        }
        $content[] = '}';

        $content = implode(PHP_EOL, $content);

        $path = $this->command->typescriptable->outputPath;
        $filename = $this->command->typescriptable->outputFile;

        $path = "{$path}/{$filename}";
        File::put($path, $content);
    }

    /**
     * @return ClassTemplate[]
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
                $model = ClassTemplate::make(
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