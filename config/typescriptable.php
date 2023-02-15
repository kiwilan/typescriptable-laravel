<?php

// config for Kiwilan/Typescriptable
return [
    'output_path' => resource_path('js'),
    'filename' => [
        'models' => 'types-models.d.ts',
        'routes' => 'types-routes.d.ts',
        'routes_list' => 'routes.ts',
        'ziggy' => 'types-ziggy.d.ts',
    ],
    'routes' => [
        'skip' => [
            'name' => [
                'debugbar.*',
                'horizon.*',
                'telescope.*',
                'nova.*',
                'lighthouse.*',
                'livewire.*',
                'ignition.*',
                'filament.*',
            ],
            'path' => [
                'api/*',
            ],
        ],
    ],
];
