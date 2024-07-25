<?php

use Kiwilan\Typescriptable\Typed\Route\RouteConfig;
use Kiwilan\Typescriptable\Typed\RouteType;
use Kiwilan\Typescriptable\TypescriptableConfig;

beforeEach(function () {
    deleteFile(outputDir(TypescriptableConfig::routesFilename()));
    deleteFile(outputDir(TypescriptableConfig::routesFilenameList()));

    config()->set('typescriptable.routes.filename', TypescriptableConfig::routesFilename());
    config()->set('typescriptable.routes.filename_list', TypescriptableConfig::routesFilenameList());
    config()->set('typescriptable.routes.print_list', true);
    config()->set('typescriptable.routes.add_to_window', false);
    config()->set('typescriptable.routes.use_path', false);
});

it('can type routes', function () {
    $type = RouteType::make(new RouteConfig(
        json: json_decode(file_get_contents(routes()), true),
    ));
    ray($type);
});
