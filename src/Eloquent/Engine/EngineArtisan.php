<?php

namespace Kiwilan\Typescriptable\Eloquent\Eloquent;

use Illuminate\Support\Facades\Artisan;

/**
 * `EngineArtisan` is an engine for Laravel Eloquent models from `Artisan`.
 *
 * It can parse the model class, database table, attributes, relations, and other information.
 */
class EngineArtisan extends EngineBase
{
    // public function run(): self
    // {
    //     $this->app = SchemaLaravel::make($this->config->modelsPath, $this->config->phpPath);

    //     $collect = SchemaCollection::make($this->config->modelsPath, $this->config->skipModels);
    //     $schemas = $collect->getOnlyModels();

    //     $this->app->parseBaseNamespace($schemas);

    //     $models = $this->parseModels($schemas);
    //     $this->app->setModels($models);

    //     return $this;
    // }

    // /**
    //  * @param  SchemaClass[]  $schemas
    //  * @return SchemaModel[]
    //  */
    // private function parseModels(array $schemas): array
    // {
    //     $models = [];
    //     foreach ($schemas as $schema) {
    //         Artisan::call('model:show', [
    //             'model' => $schema->getNamespace(),
    //             '--json' => true,
    //         ]);

    //         $models[$schema->getNamespace()] = SchemaModel::make(json_decode(Artisan::output(), true), $schema);
    //         $attributes = $this->parseMongoDB($schema, $this->app->getDriver());
    //         if ($attributes) {
    //             $models[$schema->getNamespace()]->setAttributes($attributes);
    //         }
    //     }

    //     return $models;
    // }
}
