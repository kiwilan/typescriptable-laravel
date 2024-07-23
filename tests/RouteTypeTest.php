<?php

use Kiwilan\Typescriptable\Typed\Route\RouteConfig;
use Kiwilan\Typescriptable\Typed\RouteType;

beforeEach(function () {
    deleteFile(outputDir('types-routes.d.ts'));
});

it('can type routes', function () {
    $type = RouteType::make(new RouteConfig(
        pathTypes: outputDir(),
        pathList: outputDir(),
        json: json_decode(file_get_contents(routes()), true),
    ));
    ray($type);
});
