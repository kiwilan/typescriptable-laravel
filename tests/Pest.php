<?php

use Dotenv\Dotenv;
use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaClass;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaModel;
use Kiwilan\Typescriptable\Tests\TestCase;
use Kiwilan\Typescriptable\TypescriptableConfig;

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
    basePath: getModelsPath(),
);

// const STORY_MODEL = SchemaModel::parser(
// );

foreach (glob('.output/*') as $file) {
    if (basename($file) !== '.gitignore') {
        if (is_dir($file)) {
            rmdir($file);
        } else {
            unlink($file);
        }
    }
}

function DriverEnums(): array
{
    $dotenv = Dotenv::createMutable(getcwd());
    $data = $dotenv->load();
    $types = $data['DATABASE_TYPES'] ?? 'mysql,mariadb,sqlite,pgsql,sqlsrv';
    $types = explode(',', $types);

    return $types;
}

function DriverEnumsWithoutSqlsrv(): array
{
    $drivers = DriverEnums();
    if (($key = array_search('sqlsrv', $drivers)) !== false) {
        unset($drivers[$key]);
    }

    return $drivers;
}

function outputDir(?string $file = null): string
{
    $currentDir = getcwd();

    if ($file) {
        return "{$currentDir}/tests/output/{$file}";
    }

    return "{$currentDir}/tests/output";
}

function getModelsPath(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/Models";
}

function getPhpPath(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/output";
}

function eloquentConfig(string $eloquentEngine = 'artisan'): void
{
    deleteFile(outputDir(TypescriptableConfig::eloquentFilename()));
    deleteDir(outputDir(TypescriptableConfig::eloquentPhpPath()));

    config()->set('typescriptable.output_path', outputDir());
    config()->set('typescriptable.engine.eloquent', $eloquentEngine);
    config()->set('typescriptable.eloquent.directory', getModelsPath());
    config()->set('typescriptable.eloquent.php_path', outputDir('php'));
    config()->set('typescriptable.eloquent.paginate', true);
    config()->set('typescriptable.eloquent.skip', [
        'Kiwilan\\Typescriptable\\Tests\\Data\\Models\\SushiTest',
    ]);
}

function routes(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/routes.json";
}

function settings(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/Settings";
}

function deleteFile(string $file): void
{
    if (file_exists($file)) {
        unlink($file);
    }
}

function deleteDir(string $dir): void
{
    $files = glob("{$dir}/*");
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        if ($file === '.gitignore') {
            continue;
        }
        if (is_file($file)) {
            unlink($file);
        } elseif (is_dir($file)) {
            deleteDir($file);
            rmdir($file);
        }
    }
}

function settingsDir(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/Settings";
}

function setttingsOutputDir(): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/output";
}

function settingsExtends(): string
{
    return 'Kiwilan\Typescriptable\Tests\Data\Settings\Settings';
}

function getModelPath(string $model, string $extension = 'php'): string
{
    $currentDir = getcwd();
    $model = str_replace('\\', '/', $model);
    $model = str_replace('App/Models/', '', $model);

    return "{$currentDir}/tests/Data/Models/{$model}.{$extension}";
}

function getArtisanOutput(string $file): string
{
    $currentDir = getcwd();

    return "{$currentDir}/tests/Data/ModelsJson/{$file}.json";
}

function getModelSpl(string $file): SplFileInfo
{
    $currentDir = getcwd();

    return new SplFileInfo("{$currentDir}/tests/Data/Models/{$file}.php");
}

uses(TestCase::class)->in(__DIR__);
