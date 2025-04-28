<?php

namespace Kiwilan\Typescriptable\Eloquent\Engine;

use Illuminate\Database\Eloquent\Model;
use Kiwilan\Typescriptable\Eloquent\Database\DatabaseTable;
use Kiwilan\Typescriptable\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Eloquent\Parser\ParserRelation;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;

/**
 * `EngineParser` is a manual engine for Laravel Eloquent models.
 *
 * It can parse the model class, database table, attributes, relations, and other information.
 */
class EngineParser extends EngineBase
{
    public static function run(EloquentConfig $config = new EloquentConfig): self
    {
        $self = new self(config: $config);
        $self->parse(enableParser: true);

        return $self;
    }

    protected function parseModels(array $classes): array
    {
        return [];
    }

    // /**
    //  * Parse models from the given array of `SchemaClass`.
    //  *
    //  * @param  SchemaClass[]  $models
    //  * @return SchemaModel[]
    //  */
    // private function parseModels(array $models): array
    // {
    //     $items = [];
    //     foreach ($models as $model) {
    //         $namespace = $model->getNamespace();
    //         /** @var Model */
    //         $instance = new $namespace;
    //         $tableName = $instance->getTable();

    //         if ($this->laravel->getDatabasePrefix()) {
    //             $tableName = $this->laravel->getDatabasePrefix().$tableName;
    //         }

    //         $table = $this->parseModel($instance, $this->laravel->getDatabasePrefix());
    //         $relations = $this->parseRelations($model->getReflect());

    //         // $items[$model->getNamespace()] = SchemaModel::make([
    //         //     'class' => $model->getNamespace(),
    //         //     'database' => $this->app->getDriver(),
    //         //     'table' => $tableName,
    //         //     'attributes' => $table->getAttributes(),
    //         //     'relations' => $relations,
    //         // ], $model);

    //         $items[$model->getNamespace()] = SchemaModel::parser(
    //             class: $model,
    //             driver: $this->laravel->getDriver(),
    //             table: $tableName,
    //             attributes: $table->getAttributes(),
    //             relations: $relations,
    //         );

    //         // check mongodb here
    //         $attributes = $this->parseMongoDB($model, $this->laravel->getDriver());
    //         if ($attributes) {
    //             $items[$model->getNamespace()]->setAttributes($attributes);
    //         }
    //     }

    //     return $items;
    // }

    // /**
    //  * Parse model to find attributes.
    //  *
    //  * Define database table, fillables, hiddens, casts, and accessors.
    //  */
    // private function parseModel(Model $model, ?string $prefix): DatabaseTable
    // {
    //     $table = DatabaseTable::make($prefix.$model->getTable());

    //     $fillables = $model->getFillable();
    //     $hiddens = $model->getHidden();
    //     $casts = $model->getCasts();
    //     $accessors = $this->parseAccessors($model);

    //     foreach ($table->getAttributes() as $attribute) {
    //         if (in_array($attribute->getName(), $fillables)) {
    //             $attribute->setFillable(true);
    //         }

    //         if (in_array($attribute->getName(), $hiddens)) {
    //             $attribute->setHidden(true);
    //         }

    //         if (array_key_exists($attribute->getName(), $casts)) {
    //             $attribute->setCast($casts[$attribute->getName()]);
    //         }
    //     }

    //     foreach ($accessors as $accessor) {
    //         $table->addAttribute($accessor);
    //     }

    //     return $table;
    // }

    // /**
    //  * Parse model to find accessors.
    //  *
    //  * @return SchemaAttribute[]
    //  */
    // private function parseAccessors(Model $model): array
    // {
    //     $accessors = [];
    //     foreach ($model->getMutatedAttributes() as $attribute) {
    //         $accessors[] = new SchemaAttribute(
    //             name: $attribute,
    //             databaseType: null,
    //             increments: false,
    //             nullable: true,
    //             default: null,
    //             unique: false,
    //             appended: true,
    //             cast: 'accessor',
    //         );
    //     }

    //     return $accessors;
    // }

    // /**
    //  * Parse model to find relations.
    //  *
    //  * @return array<string, array<string, string>>
    //  */
    // private function parseRelations(\ReflectionClass $reflect): array
    // {
    //     $relations = [];

    //     // Parse every model methods
    //     foreach ($reflect->getMethods() as $method) {
    //         // Skip methods without return type
    //         if (! $method->getReturnType()) {
    //             continue;
    //         }

    //         // Determine Laravel relations
    //         // Skip if not a Laravel relation
    //         if (! str_contains($method->getReturnType(), 'Illuminate\Database\Eloquent\Relations')) {
    //             continue;
    //         }

    //         // Parse each relation
    //         $relation = ParserRelation::make($method, $this->getLaravel()->getBaseNamespace());
    //         $relations[$relation->getName()] = [
    //             'name' => $relation->getName(),
    //             'type' => $relation->getType(),
    //             'related' => $relation->getRelated(),
    //         ];
    //     }

    //     return $relations;
    // }
}
