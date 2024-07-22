<?php

use Kiwilan\Typescriptable\Typed\SettingType;

beforeEach(function () {
    deleteFile(outputDir('types-routes.d.ts'));
});

it('can type settings', function () {
    $type = SettingType::make(settingsDir(), setttingsOutputDir(), settingsExtends());
});
