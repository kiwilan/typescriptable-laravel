<?php

use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;
use Kiwilan\Typescriptable\TypescriptableConfig;

/**
 * Default config for eloquent engine.
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

const STORY_ID = new SchemaAttribute(
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

const STORY_TITLE = new SchemaAttribute(
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
const STORY_PUBLISHED_AT = new SchemaAttribute(
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

const STORY_RELATION_CHAPTERS = [
    'name' => 'chapters',
    'type' => 'HasMany',
    'related' => 'Kiwilan\Typescriptable\Tests\Data\Models\Chapter',
];
const STORY_RELATION_CATEGORY = [
    'name' => 'category',
    'type' => 'BelongsTo',
    'related' => 'Kiwilan\Typescriptable\Tests\Data\Models\Category',
];

const STORY_CLASS = SchemaClass::make(
    file: getModelSpl('Story'),
    basePath: pathModels(),
);

// const STORY_MODEL = SchemaModel::parser(
// );
