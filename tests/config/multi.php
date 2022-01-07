<?php

return [

    /*
     * Laravel-multi name.
     */
    'name' => 'Laravel-multi',

    /*
     * Logo in multi panel header.
     */
    'logo' => '<b>Laravel</b> multi',

    /*
     * Mini-logo in multi panel header.
     */
    'logo-mini' => '<b>La</b>',

    /*
     * Route configuration.
     */
    'route' => [

        'prefix' => 'multi',

        'namespace' => 'App\\Multi\\Controllers',

        'middleware' => ['web', 'multi'],
    ],

    /*
     * Laravel-multi install directory.
     */
    'directory' => app_path('Multi'),

    /*
     * Laravel-multi html title.
     */
    'title' => 'Multi',

    /*
     * Use `https`.
     */
    'secure' => false,

    /*
     * Laravel-multi auth setting.
     */
    'auth' => [
        'guards' => [
            'multi' => [
                'driver'   => 'session',
                'provider' => 'multi',
            ],
        ],

        'providers' => [
            'multi' => [
                'driver' => 'eloquent',
                'model'  => Micro\Multi\Auth\Database\Multiistrator::class,
            ],
        ],
    ],

    /*
     * Laravel-multi upload setting.
     */
    'upload' => [

        'disk' => 'multi',

        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
     * Laravel-multi database setting.
     */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'multi_users',
        'users_model' => Micro\Multi\Auth\Database\Multiistrator::class,

        // Role table and model.
        'roles_table' => 'multi_roles',
        'roles_model' => Micro\Multi\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'multi_permissions',
        'permissions_model' => Micro\Multi\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'multi_menu',
        'menu_model' => Micro\Multi\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'multi_operation_log',
        'user_permissions_table' => 'multi_user_permissions',
        'role_users_table'       => 'multi_role_users',
        'role_permissions_table' => 'multi_role_permissions',
        'role_menu_table'        => 'multi_role_menu',
    ],

    /*
     * By setting this option to open or close operation log in laravel-multi.
     */
    'operation_log' => [

        'enable' => true,

        /*
         * Routes that will not log to database.
         *
         * All method to path like: multi/auth/logs
         * or specific method to path like: get:multi/auth/logs
         */
        'except' => [
            'multi/auth/logs*',
        ],
    ],

    /*
     * @see https://multilte.io/docs/2.4/layout
     */
    'skin' => 'skin-blue-light',

    /*
    |---------------------------------------------------------|
    |LAYOUT OPTIONS | fixed                                   |
    |               | layout-boxed                            |
    |               | layout-top-nav                          |
    |               | sidebar-collapse                        |
    |               | sidebar-mini                            |
    |---------------------------------------------------------|
     */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
     * Version displayed in footer.
     */
    'version' => '1.5.x-dev',

    /*
     * Settings for extensions.
     */
    'extensions' => [

    ],
];
