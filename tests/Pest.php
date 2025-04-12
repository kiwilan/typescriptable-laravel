<?php

use Kiwilan\Typescriptable\Tests\TestCase;

// clear output directory
foreach (glob('.output/*') as $file) {
    if (basename($file) !== '.gitignore') {
        if (is_dir($file)) {
            rmdir($file);
        } else {
            unlink($file);
        }
    }
}

include_once __DIR__.'/Utils/paths.php';
include_once __DIR__.'/Utils/methods.php';
include_once __DIR__.'/Utils/data.php';

uses(TestCase::class)->in(__DIR__);
