<?php

return [
    /**
     * Engine used for parsing.
     */
    'engine' => [
        /**
         * `artisan` will use the `php artisan model:show` command to parse the models.
         * `parser` will use internal engine to parse the models.
         */
        'eloquent' => 'artisan', // artisan / parser
    ],

    /**
     * The path to the output directory.
     */
    'output_path' => resource_path('js'),

    /**
     * Options for the Eloquent models.
     */
    'eloquent' => [
        'filename' => 'types-eloquent.d.ts',
        /**
         * The path to the models directory.
         */
        'directory' => app_path('Models'),
        /**
         * The path to print PHP classes if you want to convert Models to simple classes.
         * If null will not print PHP classes.
         */
        'php_path' => null,
        /**
         * Models to skip.
         */
        'skip' => [
            // 'App\\Models\\User',
        ],
        /**
         * Whether to add the LaravelPaginate type (with API type and view type).
         */
        'paginate' => true,
    ],
    /**
     * Options for the Spatie settings.
     */
    'settings' => [
        'filename' => 'types-settings.d.ts',
        /**
         * The path to the settings directory.
         */
        'directory' => app_path('Settings'),
        /**
         * Settings to skip.
         */
        'skip' => [
            // 'App\\Settings\\Home',
        ],
    ],
    /**
     * Options for the routes.
     */
    'routes' => [
        'filename' => 'types-routes.d.ts',
        'filename_list' => 'routes.ts',
        /**
         * Use routes `path` instead of `name` for the type name.
         */
        'use_path' => false,
        /**
         * Routes to skip.
         */
        'skip' => [
            'name' => [
                '__clockwork.*',
                'debugbar.*',
                'horizon.*',
                'telescope.*',
                'nova.*',
                'lighthouse.*',
                'livewire.*',
                'ignition.*',
                'filament.*',
                'log-viewer.*',
            ],
            'path' => [
                'api/*',
            ],
        ],
    ],
];
