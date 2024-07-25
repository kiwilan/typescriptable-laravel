<?php

use Illuminate\Support\Facades\Artisan;
use Kiwilan\Typescriptable\Typed\Utils\EloquentList;

beforeEach(function () {
    eloquentConfig();
});

it('can list models', function () {
    $list = EloquentList::make(models());

    expect($list->models())->toBeArray();
    expect($list->path())->toBe(models());
    expect(count($list->models()))->toBe(8);
});

it('can use command', function () {
    Artisan::call('eloquent:list');

    $output = Artisan::output();
    expect($output)->toContain('Name');
    expect($output)->toContain('Namespace');
    expect($output)->toContain('Path');
});
