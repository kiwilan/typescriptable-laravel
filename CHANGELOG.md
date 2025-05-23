# Changelog

All notable changes to `typescriptable-laravel` will be documented in this file.

## v3.1.06 - 2025-03-22

Support Laravel 12 by #96

## v3.1.05 - 2024-12-23

- Fix for [SchemaClass](https://github.com/kiwilan/typescriptable-laravel/blob/main/src/Typed/Utils/Schema/SchemaClass.php) for #89, thanks to @PerryRylance
- Upgrade `mongodb/laravel-mongodb` to v5.x.x

## v3.1.03 - 2024-07-25

- Fix missing accessors with Eloquent `parser` engine

## v3.1.02 - 2024-07-25

- Fix `isModel()` detection, add `Illuminate\Foundation\Auth\User` for models with `Authenticatable` trait.
- Add `snakeCaseName` property to relations to get `count` with snake case name.

## v3.1.01 - 2024-07-25

- Fix `RouteListCommand` with JSON format
- Fix `}` for Routes types

## v3.1.0 - 2024-07-25

- Remove options from `typescriptable:eloquent`, `typescriptable:settings` and `typescriptable:routes` because all parameters can be set from config
- Add `eloquent:list` to show all Eloquent models
- Routes are now generated from `route:list` command
- Add more tests for routes and settings
- Add config for routes
  - `routes.print_list` to print `routes.ts` file
  - `routes.add_to_window` to add routes into `window` to get it from `window.Routes` (SSR check), `routes.print_list` must be `true`
  - `routes.use_path` to replace routes types names to routes paths
  

## v3.0.0 - 2024-07-22

Refactoring with Artisan command `show:model`

- `typescriptable:models` command is now `typescriptable:eloquent` command (old command still works)
- Add more tests to valid Eloquent parsing
- Add mongodb support
- Add new option into config to handle engine with two options `artisan` or `parser`
  - `artisan` will parse models with Artisan command `show:model`
  - `parser` will parse models with internal engine
  

```php
'engine' => [
  /**
   * `artisan` will use the `php artisan model:show` command to parse the models.
   * `parser` will use internal engine to parse the models.
   */
  'eloquent' => 'artisan', // artisan / parser
],







```
**BREAKING CHANGES**

- Change `models` entry into config to `eloquent`

```diff
- 'models => [
+ 'eloquent' => [
  // ...
],







```
## v2.0.07 - 2024-06-14

- Fix `EloquentPhp::class` for `\` duplicates
- Fix `EloquentCast::class` with `UnitEnum` and public constants into enum classes

## v2.0.06 - 2024-04-15

Fix `window.Routes` from `routes.ts` auto-generated file

## v2.0.05 - 2024-04-15

Fix version

## v2.0.04 - 2024-04-15

Add filter on `items()` to keep only class with inheritance from `Model` class

## v2.0.03 - 2024-04-08

Routes generation fixes with ESLint (typo, multiple params).

## v2.0.02 - 2024-03-30

Fix `window.Routes = Routes` for `routes.ts`

## v2.0.01 - 2024-03-30

Add `appUrl` to `routes.ts`.

## v2.0.0 - 2024-03-16

Drop Laravel 9 and Laravel 10 and PHP 8.1 support (Laravel 11 support only PHP 8.2+).

> Laravel is no longer dependent on the Doctrine DBAL and registering custom Doctrines types is no longer necessary for the proper creation and alteration of various column types that previously required custom types.
From [Laravel News](https://laravel-news.com/laravel-11)

To install package with old versions of Laravel, use the following command:

```bash
composer require kiwilan/typescriptable-laravel:1.12.03















```
## v1.12.03 - 2024-03-16

Readd `doctrine/dbal` for Laravel < 11.

## v1.12.02 - 2024-03-16

Drop `doctrine/dbal` to `require-dev` and update dependencies.

## v1.12.0 - 2024-03-16

Add support for Laravel 11

## v1.11.40 - 2024-02-01

Hotfix for relation parsing if multiline.

## v1.11.36 - 2024-01-13

- `DateTime` type is now `string` for Typescript

For issue #46

## v1.11.35 - 2024-01-11

- Fix

## v1.11.34 - 2024-01-11

- Hotfix for `v1.11.33`

## v1.11.33 - 2024-01-10

- Add config option for database prefix `DB_PREFIX` (can be used into `database.php` file)

Issue #44

## v1.11.32 - 2024-01-06

- Fix EloquentRelation prefix morph relations

## v1.11.31 - 2024-01-06

- Replace `Schema::getAllTables()` with `Schema::getConnection()->getDoctrineSchemaManager()->listTableNames()`

## v1.11.30 - 2024-01-06

- Add `pivot` parser with `EloquentRelation` parser to add `pivot` property to original model.

## v1.11.21 - 2023-12-16

- Fix `EloquentRelation` warning if `type` is not exist

## v1.11.14 - 2023-10-21

- Fix `[Bug]: linting problem` #36

## v1.11.13 - 2023-10-15

- Fixes

## v1.11.12 - 2023-10-15

- Models relationships fixes

## v1.11.11 - 2023-10-02

- Typescript generated fixed

## v1.11.10 - 2023-10-02

- Add header to `.d.ts` to skip Typescript errors

## 1.11.03 - 2023-08-09

- Fix `Typescriptable::settings` return type to `?SettingType`
- `Table` will now check if table exists before trying to scan it
- `ClassItem` fix config skip model

## 1.11.02 - 2023-08-09

- Fix `mixed` ts type to `any`
- Fix `SettingType` allow path to not exists

## 1.11.01 - 2023-08-09

- Add `EloquentRelation` fallback for `type` and `typeTs`

## 1.11.0 - 2023-06-30

- fix version

## 1.5.20 - 2023-06-30

- Add advanced array support to the for PHPDoc tag.

## 1.5.10 - 2023-06-29

- `SettingType` fix `extends` param if `null`

## 1.5.0 - 2023-06-29

- Add partial support for `spatie/laravel-settings` package

## 1.4.0 - 2023-06-18

- add support for `Morph` relations

## 1.3.0 - 2023-06-02

- Fix `Illuminate\Database\Eloquent\Casts\Attribute` bug

## 1.2.0 - 2023-04-04

- Add tests
- Multiple database support (`sqlserver` soon) for issue #4

## 1.1.13 - 2023-03-14

- Fix same name routes

## 1.1.12 - 2023-03-14

- Rename Route to App.Route

## 1.1.11 - 2023-03-14

- Fix routes when method is not exist

## 1.1.10 - 2023-03-14

- Fix crash on Laravel 10

## 1.1.001 - 2023-03-08

- Now compatible with Laravel 10

## 1.1.0 - 2023-02-22

- Remove `inertia` command
- Inertia types are now generated by NPM associated package
- `TypedLink` is now `Route`

## 1.0.0 - 2023-02-21

- Refactoring of `typescriptable:models` command
- Add `typescriptable:routes` command to generate route types
- Add `typescriptable:inertia` command to generate Inertia types
- Add `typescriptable` command to generate current commands with options
- All commands options are now into package config
- Publish `@kiwilan/typescriptable-laravel` with some features for Inertia and typed routes usage

## 0.2.21 - 2023-02-13

- remove `string[]` for `flash`, only `object` type

## 0.2.20 - 2023-02-13

- Add `flash` into `InertiaPage`

## 0.2.1 - 2023-02-08

- Fix namespace

## 0.2.0 - 2023-02-08

- Add new command `typescriptable:ziggy` to add Laravel routes types and Inertia extra types (for `usePage` and global methods in Vue components)

## 0.1.12 - 2023-02-07

- Improve pagination
