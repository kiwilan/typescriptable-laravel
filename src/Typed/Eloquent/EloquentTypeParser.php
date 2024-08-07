<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Typed\Database\Table;
use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserRelation;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModelAttribute;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\SchemaApp;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;

class EloquentTypeParser extends EloquentType implements IEloquentType
{
    public function run(): self
    {
        $this->app = SchemaApp::make($this->config->modelsPath, $this->config->phpPath)->enableParer();

        $collect = SchemaCollection::make($this->config->modelsPath, $this->config->skipModels);
        $schemas = $collect->onlyModels();

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
            $namespace = $schema->namespace();
            /** @var Model */
            $instance = new $namespace;
            $tableName = $instance->getTable();

            if ($this->app->databasePrefix()) {
                $tableName = $this->app->databasePrefix().$tableName;
            }

            $table = $this->parseModel($instance, $this->app->databasePrefix());
            $relations = $this->parseRelations($schema->reflect());

            $models[$schema->namespace()] = SchemaModel::make([
                'class' => $schema->namespace(),
                'database' => $this->app->driver(),
                'table' => $tableName,
                'attributes' => $table->attributes(),
                'relations' => $relations,
            ], $schema);

            $attributes = $this->parseMongoDb($schema, $this->app->driver());
            if ($attributes) {
                $models[$schema->namespace()]->setAttributes($attributes);
            }
        }

        return $models;
    }

    private function parseModel(Model $model, ?string $prefix): Table
    {
        $table = Table::make($prefix.$model->getTable());

        $fillables = $model->getFillable();
        $hiddens = $model->getHidden();
        $casts = $model->getCasts();
        $accessors = $this->parseAccessors($model);

        foreach ($table->attributes() as $attribute) {
            if (in_array($attribute->name(), $fillables)) {
                $attribute->isFillable();
            }

            if (in_array($attribute->name(), $hiddens)) {
                $attribute->isHidden();
            }

            if (array_key_exists($attribute->name(), $casts)) {
                $attribute->setCast($casts[$attribute->name()]);
            }
        }

        foreach ($accessors as $accessor) {
            $table->addAttribute($accessor);
        }

        return $table;
    }

    /**
     * @return SchemaModelAttribute[]
     */
    private function parseAccessors(Model $model): array
    {
        $accessors = [];
        foreach ($model->getMutatedAttributes() as $attribute) {
            $accessors[] = new SchemaModelAttribute(
                name: $attribute,
                databaseType: null,
                increments: false,
                nullable: true,
                default: null,
                unique: false,
                appended: true,
                cast: 'accessor',
            );
        }

        return $accessors;
    }

    private function parseRelations(\ReflectionClass $reflect)
    {
        $relations = [];

        foreach ($reflect->getMethods() as $method) {
            if (! $method->getReturnType()) {
                continue;
            }

            $isRelation = str_contains($method->getReturnType(), 'Illuminate\Database\Eloquent\Relations');

            if (! $isRelation) {
                continue;
            }

            $relation = ParserRelation::make($method, $this->app()->baseNamespace());
            $relations[$relation->name()] = [
                'name' => $relation->name(),
                'type' => $relation->type(),
                'related' => $relation->related(),
            ];
        }

        return $relations;
    }
}
