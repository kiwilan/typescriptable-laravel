<?php

namespace Kiwilan\Typescriptable\Eloquent\Engine;

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Eloquent\EloquentConfig;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;

/**
 * `EngineArtisan` is an engine for Laravel Eloquent models from `Artisan`.
 *
 * It can parse the model class, database table, attributes, relations, and other information.
 */
class EngineArtisan extends EngineBase
{
    public static function run(EloquentConfig $config = new EloquentConfig): self
    {
        $self = new self(config: $config);
        $self->parse();

        return $self;
    }

    protected function parseModels(array $classes): array
    {
        $models = [];
        foreach ($classes as $class) {
            // Get model information from Artisan
            Artisan::call('model:show', [
                'model' => $class->getNamespace(),
                '--json' => true,
            ]);

            // Create a new SchemaModel instance with output
            $models[$class->getNamespace()] = $this->createModel(
                class: $class,
                artisan: json_decode(Artisan::output(), true),
            );

            // $attributes = $this->parseMongoDB($class, $this->laravel->getDriver());
            // if ($attributes) {
            //     $models[$class->getNamespace()]->setAttributes($attributes);
            // }
        }

        return $models;
    }

    /**
     * Create a new `SchemaModel` instance from Artisan output.
     */
    private function createModel(SchemaClass $class, array $artisan): SchemaModel
    {
        $model = new SchemaModel(
            class: $class,
            driver: $this->laravel->getDriver(),
            table: $artisan['table'],
            policy: $artisan['policy'] ?? null,
        );

        $artisanAttributes = $artisan['attributes'] ?? [];
        $attributes = [];

        foreach ($artisanAttributes as $artisanAttribute) {
            $schema = $this->parseAttribute($artisanAttribute);
            $attributes[$schema->getName()] = $schema;
        }

        // $model->handleAttributes(SchemaAttribute::fromArtisan($model->getDriver(), $artisan));

        return $model;
    }

    /**
     * Parse a single attribute from Artisan output.
     */
    private function parseAttribute(array $attribute): SchemaAttribute
    {
        return new SchemaAttribute(
            name: $attribute['name'],
            driver: $this->laravel->getDriver(),
            databaseType: $attribute['type'],
            increments: $attribute['increments'],
            nullable: $attribute['nullable'],
            default: $attribute['default'],
            unique: $attribute['unique'],
            fillable: $attribute['fillable'],
            hidden: $attribute['hidden'],
            appended: $attribute['appended'],
            cast: $attribute['cast'],
        );
    }
}
