<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Eloquent\Utils\EloquentList;

beforeEach(function () {
    eloquentConfig();
});

it('can list models', function () {
    $list = EloquentList::make(getModelsPath());

    expect($list->getModelsPath())->toBeArray();
    expect($list->path())->toBe(getModelsPath());
    expect(count($list->getModelsPath()))->toBe(9);
});

it('can use command', function () {
    Artisan::call('eloquent:list');

    $output = Artisan::output();
    expect($output)->toContain('Name');
    expect($output)->toContain('Namespace');
    expect($output)->toContain('Path');
});
