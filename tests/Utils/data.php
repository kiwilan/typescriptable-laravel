<?php

use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;
use Kiwilan\Typescriptable\TypescriptableConfig;

/**
 * Default config for eloquent engine.
 *
 * @param  string  $eloquentEngine  Could be `artisan` or `eloquent`.
 */
function eloquentConfig(string $eloquentEngine = 'artisan'): void
{
    deleteFile(pathOutput(TypescriptableConfig::eloquentFilename()));
    deleteDirectory(pathOutput(TypescriptableConfig::eloquentPhpPath()));

    config()->set('typescriptable.output_path', pathOutput());
    config()->set('typescriptable.engine.eloquent', $eloquentEngine);
    config()->set('typescriptable.eloquent.directory', pathModels());
    config()->set('typescriptable.eloquent.php_path', pathOutput('php'));
    config()->set('typescriptable.eloquent.paginate', true);
    config()->set('typescriptable.eloquent.skip', [
        'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
    ]);
}

/**
 * Seed `id` for `Story` model.
 */
function seedStoryId(): SchemaAttribute
{
    return new SchemaAttribute(
        name: 'id',
        driver: DriverEnum::mysql,
        databaseType: 'bigint unsigned',
        increments: true,
        nullable: false,
        default: null,
        unique: false,
        fillable: false,
        hidden: false,
        appended: null,
        cast: 'int',
        phpType: null,
        typescriptType: null,
        databaseFields: [
            'Field' => 'id',
            'Type' => 'bigint unsigned',
            'Null' => 'NO',
            'Key' => 'PRI',
            'Default' => null,
            'Extra' => 'auto_increment',
        ],
    );
}

/**
 * Seed `title` for `Story` model.
 */
function seedStoryTitle(): SchemaAttribute
{
    return new SchemaAttribute(
        name: 'title',
        driver: DriverEnum::mysql,
        databaseType: 'varchar(255)',
        increments: false,
        nullable: false,
        default: null,
        unique: false,
        fillable: true,
        hidden: false,
        appended: null,
        cast: null,
        phpType: null,
        typescriptType: null,
        databaseFields: [
            'Field' => 'title',
            'Type' => 'varchar(255)',
            'Null' => 'NO',
            'Key' => '',
            'Default' => null,
            'Extra' => '',
        ],
    );
}

/**
 * Seed `published_at` for `Story` model.
 */
function seedStoryPublishedAt(): SchemaAttribute
{
    return new SchemaAttribute(
        name: 'published_at',
        driver: DriverEnum::mysql,
        databaseType: 'datetime',
        increments: false,
        nullable: true,
        default: null,
        unique: false,
        fillable: true,
        hidden: false,
        appended: null,
        cast: 'datetime',
        phpType: null,
        typescriptType: null,
        databaseFields: [
            'Field' => 'published_at',
            'Type' => 'datetime',
            'Null' => 'YES',
            'Key' => '',
            'Default' => null,
            'Extra' => '',
        ],
    );
}

/**
 * Seed raw relation `Category` for `Story` model.
 */
function seedStoryRelationCategory(): array
{
    return [
        'name' => 'category',
        'type' => 'BelongsTo',
        'related' => 'Kiwilan\Typescriptable\Tests\Data\Models\Category',
    ];
}

/**
 * Seed raw relation `Chapter` for `Story` model.
 */
function seedStoryRelationChapters(): array
{
    return [
        'name' => 'chapters',
        'type' => 'HasMany',
        'related' => 'Kiwilan\Typescriptable\Tests\Data\Models\Chapter',
    ];
}

/**
 * Seed `Story` PHP class.
 */
function seedStoryClass(): SchemaCLass
{
    return SchemaClass::make(
        file: getModelSpl('Story'),
        basePath: pathModels(),
    );
}

// const STORY_MODEL = SchemaModel::parser(
// );
