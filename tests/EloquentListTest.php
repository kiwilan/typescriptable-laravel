<?php

it('can list models', function () {
    $list = ModelList::make(models());

    expect($list->models())->toBeArray();
    expect($list->path())->toBe(models());
    expect(count($list->models()))->toBe(10);

    Artisan::call('model:list', [
        'modelPath' => models(),
    ]);

    $output = Artisan::output();
    expect($output)->toContain('Name');
    expect($output)->toContain('Namespace');
    expect($output)->toContain('Path');
});
