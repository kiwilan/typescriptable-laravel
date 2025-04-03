<?php

namespace Kiwilan\Typescriptable\Eloquent\Eloquent;

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaCollection;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaLaravel;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;

/**
 * `EngineArtisan` is an engine for Laravel Eloquent models from `Artisan`.
 *
 * It can parse the model class, database table, attributes, relations, and other information.
 */
class EngineArtisan extends EngineBase
{
    /**
     * Execute the engine.
     */
    public function run(): self
    {
        $this->laravel = SchemaLaravel::make(
            modelPath: $this->config->modelsPath,
            phpPath: $this->config->phpPath,
        );

        $collect = SchemaCollection::make(
            basePath: $this->config->modelsPath,
            skip: $this->config->skipModels,
        );
        $schemas = $collect->getOnlyModels();

        $this->laravel->parseBaseNamespace($schemas);

        $models = $this->parseModels($schemas);
        $this->laravel->setModels($models);

        return $this;
    }

    /**
     * Parse models from Laravel Artisan command.
     *
     * @param  SchemaClass[]  $classes
     * @return SchemaModel[]
     */
    private function parseModels(array $classes): array
    {
        $models = [];
        foreach ($classes as $class) {
            Artisan::call('model:show', [
                'model' => $class->getNamespace(),
                '--json' => true,
            ]);

            $models[$class->getNamespace()] = SchemaModel::fromArtisan(
                class: $class,
                driver: $this->laravel->getDriver(),
                artisan: json_decode(Artisan::output(), true),
            );

            $attributes = $this->parseMongoDB($class, $this->laravel->getDriver());
            if ($attributes) {
                $models[$class->getNamespace()]->setAttributes($attributes);
            }
        }

        return $models;
    }
}
