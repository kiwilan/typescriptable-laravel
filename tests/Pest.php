<?php

use Kiwilan\Typescriptable\Tests\TestCase;

define('DATABASE_TYPES', [
    'mysql',
    'pgsql',
    'sqlite',
    'sqlsrv',
]);

uses(TestCase::class)->in(__DIR__);
