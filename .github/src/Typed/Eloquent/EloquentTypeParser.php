<?php

namespace Kiwilan\Typescriptable\Typed\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Typed\Database\Table;
use Kiwilan\Typescriptable\Typed\Eloquent\Parser\ParserRelation;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\Model\SchemaModel;
use Kiwilan\Typescriptable\Typed\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Typed\Eloquent\Schemas\SchemaApp;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaClass;
use Kiwilan\Typescriptable\Typed\Utils\Schema\SchemaCollection;

class EloquentTypeParser extends EloquentType implements IEloquentType
{
    public function run(): self
    {
        $this->app = SchemaApp::make($this->config->modelsPath, $this->config->phpPath)->enableParer();

        $collect = SchemaCollection::make($this->config->modelsPath, $this->config->skipModels);
        $schemas = $collect->getOnlyModels();

        $this->app->parseBaseNamespace($schemas);

        $models = $this->parseModels($schemas);
        $this->app->setModels($models);

        return $this;
    }

    /**
     * @param  SchemaClass[]  $classes
     * @return SchemaModel[]
     */
    private function parseModels(array $classes): array
    {
        $models = [];
        foreach ($classes as $class) {
            $namespace = $class->getNamespace();
            /** @var Model */
            $instance = new $namespace;
            $tableName = $instance->getTable();

            if ($this->app->getDatabasePrefix()) {
                $tableName = $this->app->getDatabasePrefix().$tableName;
            }

            $table = $this->parseModel($instance, $this->app->getDatabasePrefix());
            $relations = $this->parseRelations($class->getReflect());

            // $models[$class->getNamespace()] = SchemaModel::make([
            //     'class' => $class->getNamespace(),
            //     'database' => $this->app->getDriver(),
            //     'table' => $tableName,
            //     'attributes' => $table->getAttributes(),
            //     'relations' => $relations,
            // ], $class);

            $models[$class->getNamespace()] = SchemaModel::make(
                schemaClass: $class,
                namespace: $class->getNamespace(),
                driver: $this->app->getDriver(),
                table: $tableName,
                attributes: $table->getAttributes(),
                relations: $relations,
            );

            $attributes = $this->parseMongoDb($class, $this->app->getDriver());
            if ($attributes) {
                $models[$class->getNamespace()]->setAttributes($attributes);
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
            if (in_array($attribute->getName(), $fillables)) {
                $attribute->setFillable(true);
            }

            if (in_array($attribute->getName(), $hiddens)) {
                $attribute->setHidden(true);
            }

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
     * @return SchemaAttribute[]
     */
    private function parseAccessors(Model $model): array
    {
        $accessors = [];
        foreach ($model->getMutatedAttributes() as $attribute) {
            $accessors[] = new SchemaAttribute(
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

            $relation = ParserRelation::make($method, $this->getApp()->getBaseNamespace());
            $relations[$relation->getName()] = [
                'name' => $relation->getName(),
                'type' => $relation->getType(),
                'related' => $relation->getRelated(),
            ];
        }

        return $relations;
    }
}
