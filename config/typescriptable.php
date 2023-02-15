<?php

// config for Kiwilan/Typescriptable
return [
    'output_path' => resource_path('js'),
    'models' => [
        'filename' => 'types-models.d.ts',
        'directory' => app_path('Models'),
        'skip' => [
            // 'App\\Models\\User',
        ],
        'paginate' => true,
        'fake_team' => false,
    ],
    'routes' => [
        'filename' => 'types-routes.d.ts',
        'filename_list' => 'routes.ts',
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
    'inertia' => [
        'filename' => 'types-inertia.d.ts',
        'page' => true,
        'use_embed' => false,
    ],
];
