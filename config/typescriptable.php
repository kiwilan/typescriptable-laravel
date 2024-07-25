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
     * The path to the output directory for all the types.
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
         *
         * @example `app_path('Raw')`
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
         * Extended class for the settings.
         */
        'extends' => 'Spatie\LaravelSettings\Settings',
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
        /**
         * The path to the routes types.
         */
        'types' => 'types-routes.d.ts',
        /**
         * The path to the routes list.
         */
        'list' => 'routes.ts',
        /**
         * Whether to print the list of routes.
         */
        'print_list' => true,
        /**
         * Add routes to `window` from list, can be find with `window.Routes`.
         */
        'add_to_window' => true,
        /**
         * Use routes `path` instead of `name` for the type name.
         */
        'use_path' => false,
        /**
         * Routes to skip.
         */
        'skip' => [
            'name' => [
                'debugbar.*',
                'horizon.*',
                'telescope.*',
                'nova.*',
                'lighthouse.*',
                'filament.*',
                'log-viewer.*',
                'two-factor.*',
            ],
            'path' => [
                '_ignition/*',
                '__clockwork/*',
                'clockwork/*',
                'two-factor-challenge',
                'livewire',
            ],
        ],
    ],
];
