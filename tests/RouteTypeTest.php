<?php

use Kiwilan\Typescriptable\Typed\RouteType;

beforeEach(function () {
    deleteFile(outputDir('types-routes.d.ts'));
});

it('can type routes', function () {
    $type = RouteType::make(routes(), true, outputDir());
    ray($type);
});
