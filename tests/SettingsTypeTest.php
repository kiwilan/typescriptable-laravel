<?php

use Kiwilan\Typescriptable\Typed\SettingsType;

beforeEach(function () {
    deleteFile(outputDir('types-routes.d.ts'));
});

it('can type settings', function () {
    // settingsDir(), setttingsOutputDir(), settingsExtends()
    $type = SettingsType::make();
});
