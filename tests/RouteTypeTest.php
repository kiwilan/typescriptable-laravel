<?php

use Kiwilan\Typescriptable\Typed\Route\RouteConfig;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItem;
use Kiwilan\Typescriptable\Typed\Route\Schemas\RouteTypeItemParam;
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
    $routes = $type->routes();

    $config = $type->config();

    expect($config->filenameTypes)->toBe(TypescriptableConfig::routesFilename());
    expect($config->filenameList)->toBe(TypescriptableConfig::routesFilenameList());
    expect($config->printList)->toBeTrue();
    expect($config->json)->toBeArray();
    expect($config->namesToSkip)->toBeArray();
    expect($config->pathsToSkip)->toBeArray();

    expect($routes->toArray())->toBeArray();
    expect($routes->toArray())->not->toBeEmpty();
    expect($routes->count())->toBe(60);

    $typescriptList = $type->typescriptList();
    expect($typescriptList)->toBeString();

    $typescriptTypes = $type->typescriptTypes();
    expect($typescriptTypes)->toBeString();

    $getHome = $routes->get('get.home');
    expect($getHome)->toBeInstanceOf(RouteTypeItem::class);
    expect($getHome->uri())->toBe('/');
    expect($getHome->name())->toBe('home');
    expect($getHome->action())->toBe('App\Http\Controllers\App\HomeController@index');
    expect($getHome->methodMain())->toBe('GET');
    expect($getHome->methods())->toBeArray();
    expect($getHome->methods())->toContain('GET');
    expect($getHome->methods())->toContain('HEAD');
    expect($getHome->middlewares())->toBeArray();
    expect($getHome->middlewares())->toContain('web');
    expect($getHome->middlewares())->toContain('Illuminate\Routing\Middleware\SubstituteBindings');
    expect($getHome->parameters())->toBeArray();
    expect($getHome->parameters())->toBeEmpty();
    expect($getHome->id())->toBe('get.home');

    $getLibrariesShow = $routes->get('get.libraries.show');
    expect($getLibrariesShow)->toBeInstanceOf(RouteTypeItem::class);
    expect($getLibrariesShow->uri())->toBe('/libraries/{library}');
    expect($getLibrariesShow->name())->toBe('libraries.show');
    expect($getLibrariesShow->action())->toBe('App\Http\Controllers\App\LibraryController@show');
    expect($getLibrariesShow->parameters())->toBeArray();
    expect($getLibrariesShow->parameters())->not->toBeEmpty();
    expect($getLibrariesShow->parameters())->toHaveCount(1);
    $param = $getLibrariesShow->parameters()[0];
    expect($param)->toBeInstanceOf(RouteTypeItemParam::class);
    expect($param->name())->toBe('library');
    expect($param->type())->toBe('string');
    expect($param->required())->toBeTrue();
    expect($param->default())->toBeNull();
    expect($getLibrariesShow->id())->toBe('get.libraries.show');

    $putUserPasswordUpdate = $routes->get('put.user.password.update');
    expect($putUserPasswordUpdate)->toBeInstanceOf(RouteTypeItem::class);
    expect($putUserPasswordUpdate->uri())->toBe('/user/password');
    expect($putUserPasswordUpdate->name())->toBe('user-password.update');
    expect($putUserPasswordUpdate->action())->toBe('Laravel\Fortify\Http\Controllers\PasswordController@update');
    expect($putUserPasswordUpdate->methodMain())->toBe('PUT');
    expect($putUserPasswordUpdate->methods())->toBeArray();
    expect($putUserPasswordUpdate->methods())->toContain('PUT');
    expect($putUserPasswordUpdate->middlewares())->toBeArray();
    expect($putUserPasswordUpdate->middlewares())->toContain('web');
    expect($putUserPasswordUpdate->middlewares())->toContain('Illuminate\Auth\Middleware\Authenticate:web');
    expect($putUserPasswordUpdate->parameters())->toBeArray();
    expect($putUserPasswordUpdate->parameters())->toBeEmpty();
    expect($putUserPasswordUpdate->id())->toBe('put.user.password.update');
});
