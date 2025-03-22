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
        $schemas = $collect->getItems(only_models: true);

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
            $namespace = $schema->getNamespace();
            /** @var Model */
            $instance = new $namespace;
            $tableName = $instance->getTable();

            if ($this->app->getDatabasePrefix()) {
                $tableName = $this->app->getDatabasePrefix().$tableName;
            }

            $table = $this->parseModel($instance, $this->app->getDatabasePrefix());
            $relations = $this->parseRelations($schema->getReflect());

            $models[$schema->getNamespace()] = SchemaModel::make([
                'class' => $schema->getNamespace(),
                'database' => $this->app->getDriver(),
                'table' => $tableName,
                'attributes' => $table->getAttributes(),
                'relations' => $relations,
            ], $schema);

            $attributes = $this->parseMongoDb($schema, $this->app->getDriver());
            if ($attributes) {
                $models[$schema->getNamespace()]->addAttributes($attributes);
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

        foreach ($table->getAttributes() as $attribute) {
            // Know if attribute is fillable
            if (in_array($attribute->getName(), $fillables)) {
                $attribute->setFillable(true);
            }

            // Know if attribute is hidden
            if (in_array($attribute->getName(), $hiddens)) {
                $attribute->setHidden(true);
            }

            // Know if attribute use Laravel cast type
            if (array_key_exists($attribute->getName(), $casts)) {
                $attribute->setCast($casts[$attribute->getName()]);
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

            $relation = ParserRelation::make($method, $this->app()->getBaseNamespace());
            $relations[$relation->name()] = [
                'name' => $relation->name(),
                'type' => $relation->type(),
                'related' => $relation->related(),
            ];
        }

        return $relations;
    }
}
