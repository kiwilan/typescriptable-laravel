<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\SchemaApp;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;

class EloquentTypeArtisan extends EloquentType implements IEloquentType
{
    public function run(): self
    {
        $this->app = SchemaApp::make($this->config->modelsPath, $this->config->phpPath);

        $collect = SchemaCollection::make($this->config->modelsPath, $this->config->skipModels);
        $schemas = $collect->getOnlyModels();

        $this->app->parseBaseNamespace($schemas);

        $models = $this->parseModels($schemas);
        $this->app->setModels($models);

        return $this;
    }

    /**
     * @param  SchemaClass[]  $schemas
     * @return SchemaModel[]
     */
    private function parseModels(array $schemas): array
    {
        $models = [];
        foreach ($schemas as $schema) {
            Artisan::call('model:show', [
                'model' => $schema->getNamespace(),
                '--json' => true,
            ]);

            $models[$schema->getNamespace()] = SchemaModel::make(json_decode(Artisan::output(), true), $schema);
            $attributes = $this->parseMongoDb($schema, $this->app->getDriver());
            if ($attributes) {
                $models[$schema->getNamespace()]->setAttributes($attributes);
            }
        }

        return $models;
    }
}
