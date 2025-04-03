<?php

use Kiwilan\Typescriptable\Eloquent\Database\DriverEnum;
use Kiwilan\Typescriptable\Eloquent\Schema\SchemaAttribute;

it('can parse model attribute', function () {
    $attribute = new SchemaAttribute(
        name: 'title',
        driver: DriverEnum::mysql,
        databaseType: 'varchar(255)',
        increments: false,
        nullable: false,
        default: 'NULL',
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

    expect($attribute->getName())->toBe('title');
    expect($attribute->getDriver())->toBe(DriverEnum::mysql);
    expect($attribute->getDatabaseType())->toBe('varchar(255)');
    expect($attribute->isIncremental())->toBeFalse();
    expect($attribute->isNullable())->toBeFalse();
    expect($attribute->getDefault())->toBeNull();
    expect($attribute->isUnique())->toBeFalse();
    expect($attribute->isFillable())->toBeTrue();
    expect($attribute->isHidden())->toBeFalse();
    expect($attribute->isAppended())->toBeFalse();
    expect($attribute->getCast())->toBeNull();
    expect($attribute->getPhpType())->toBeNull();
    expect($attribute->getTypescriptType())->toBeNull();
    expect($attribute->getDatabaseFields())->toBeArray();
    expect($attribute->getDatabaseFields())->toBe([
        'Field' => 'title',
        'Type' => 'varchar(255)',
        'Null' => 'NO',
        'Key' => '',
        'Default' => null,
        'Extra' => '',
    ]);
});

it('is SchemaAttribute', function () {
    $attribute = new SchemaAttribute(
        name: 'title',
    );
    expect($attribute)->toBeInstanceOf(SchemaAttribute::class);
});

it('can update', function () {
    $attribute = new SchemaAttribute(
        name: 'title',
    );
    expect($attribute->getName())->toBe('title');

    $attribute->update(
        new SchemaAttribute(
            name: 'title_updated',
        ),
    );
    expect($attribute->getName())->toBe('title_updated');
});

it('can use setters', function () {
    $attribute = new SchemaAttribute(
        name: 'title',
        driver: DriverEnum::mysql,
        fillable: true,
        hidden: false,
        appended: false,
        cast: null,
    );

    expect($attribute->setFillable(false)->isFillable())->toBeFalse();
    expect($attribute->setHidden(true)->isHidden())->toBeTrue();
    expect($attribute->setAppended(true)->isAppended())->toBeTrue();
    expect($attribute->setCast('string')->getCast())->toBe('string');
});

it('can parse artisan output', function () {
    $json = file_get_contents(getArtisanOutput('movie'));
    $array = json_decode($json, true);
    $attributes = SchemaAttribute::fromArtisan(DriverEnum::mysql, $array);

    expect($attributes)->toBeArray();
    expect(count($attributes))->toBe(52);
    expect($attributes)->each(function (Pest\Expectation $attribute) {
        expect($attribute->value)->toBeInstanceOf(SchemaAttribute::class);
    });
});
