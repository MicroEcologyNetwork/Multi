<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi name
    |--------------------------------------------------------------------------
    |
    | This value is the name of laravel-multi, This setting is displayed on the
    | login page.
    |
    */
    'name' => 'Laravel-multi',

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi logo
    |--------------------------------------------------------------------------
    |
    | The logo of all multi pages. You can also set it as an image by using a
    | `img` tag, eg '<img src="http://logo-url" alt="Multi logo">'.
    |
    */
    'logo' => '<b>Laravel</b> multi',

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi mini logo
    |--------------------------------------------------------------------------
    |
    | The logo of all multi pages when the sidebar menu is collapsed. You can
    | also set it as an image by using a `img` tag, eg
    | '<img src="http://logo-url" alt="Multi logo">'.
    |
    */
    'logo-mini' => '<b>La</b>',

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi bootstrap setting
    |--------------------------------------------------------------------------
    |
    | This value is the path of laravel-multi bootstrap file.
    |
    */
    'bootstrap' => app_path('Multi/bootstrap.php'),

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi bootstrap setting
    |--------------------------------------------------------------------------
    |
    | 区域与单个门派设置
    | is_on 开启配置
    | is_multi 是否同时开启以下两项设置，false 时仅验证 single
    | region 区域标识字段名称，按项目情况设置
    | single 单个门店标识字段名称，按项目情况设置
    |
    |
    */
    'multi-limit' => [
        'is_on'  => true,
        'is_multi' => true,
        'region' => 'region_id',
        'single' => 'store_id',
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi route settings
    |--------------------------------------------------------------------------
    |
    | The routing configuration of the multi page, including the path prefix,
    | the controller namespace, and the default middleware. If you want to
    | access through the root path, just set the prefix to empty string.
    |
    */
    'route' => [

        'prefix' => env('MULTI_ROUTE_PREFIX', 'multi'),

        'namespace' => 'App\\Multi\\Controllers',

        'middleware' => ['web', 'multi'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi install directory
    |--------------------------------------------------------------------------
    |
    | The installation directory of the controller and routing configuration
    | files of the multiistration page. The default is `app/Multi`, which must
    | be set before running `artisan multi::install` to take effect.
    |
    */
    'directory' => app_path('Multi'),

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi html title
    |--------------------------------------------------------------------------
    |
    | Html title for all pages.
    |
    */
    'title' => 'Multi',

    /*
    |--------------------------------------------------------------------------
    | Access via `https`
    |--------------------------------------------------------------------------
    |
    | If your page is going to be accessed via https, set it to `true`.
    |
    */
    'https' => env('MULTI_HTTPS', false),

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi auth setting
    |--------------------------------------------------------------------------
    |
    | Authentication settings for all multi pages. Include an authentication
    | guard and a user provider setting of authentication driver.
    |
    | You can specify a controller for `login` `logout` and other auth routes.
    |
    */
    'auth' => [

        'controller' => App\Multi\Controllers\AuthController::class,

        'guard' => 'multi',

        'guards' => [
            'multi' => [
                'driver'   => 'session',
                'provider' => 'multi',
            ],
        ],

        'providers' => [
            'multi' => [
                'driver' => 'eloquent',
                'model'  => MicroEcology\Multi\Auth\Database\Multiistrator::class,
            ],
        ],

        // Add "remember me" to login form
        'remember' => true,

        // Redirect to the specified URI when user is not authorized.
        'redirect_to' => 'auth/login',

        // The URIs that should be excluded from authorization.
        'excepts' => [
            'auth/login',
            'auth/logout',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi upload setting
    |--------------------------------------------------------------------------
    |
    | File system configuration for form upload files and images, including
    | disk and upload path.
    |
    */
    'upload' => [

        // Disk in `config/filesystem.php`.
        'disk' => 'multi',

        // Image and file upload path under the disk above.
        'directory' => [
            'image' => 'images',
            'file'  => 'files',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Laravel-multi database settings
    |--------------------------------------------------------------------------
    |
    | Here are database settings for laravel-multi builtin model & tables.
    |
    */
    'database' => [

        // Database connection for following tables.
        'connection' => '',

        // User tables and model.
        'users_table' => 'multi_users',
        'users_model' => MicroEcology\Multi\Auth\Database\Multiistrator::class,

        // Role table and model.
        'roles_table' => 'multi_roles',
        'roles_model' => MicroEcology\Multi\Auth\Database\Role::class,

        // Permission table and model.
        'permissions_table' => 'multi_permissions',
        'permissions_model' => MicroEcology\Multi\Auth\Database\Permission::class,

        // Menu table and model.
        'menu_table' => 'multi_menu',
        'menu_model' => MicroEcology\Multi\Auth\Database\Menu::class,

        // Pivot table for table above.
        'operation_log_table'    => 'multi_operation_log',
        'user_permissions_table' => 'multi_user_permissions',
        'role_users_table'       => 'multi_role_users',
        'role_permissions_table' => 'multi_role_permissions',
        'role_menu_table'        => 'multi_role_menu',
    ],

    /*
    |--------------------------------------------------------------------------
    | User operation log setting
    |--------------------------------------------------------------------------
    |
    | By setting this option to open or close operation log in laravel-multi.
    |
    */
    'operation_log' => [

        'enable' => true,

        /*
         * Only logging allowed methods in the list
         */
        'allowed_methods' => ['GET', 'HEAD', 'POST', 'PUT', 'DELETE', 'CONNECT', 'OPTIONS', 'TRACE', 'PATCH'],

        /*
         * Routes that will not log to database.
         *
         * All method to path like: multi/auth/logs
         * or specific method to path like: get:multi/auth/logs.
         */
        'except' => [
            env('MULTI_ROUTE_PREFIX', 'multi').'/auth/logs*',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check route permission.
    |--------------------------------------------------------------------------
    */
    'check_route_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Indicates whether to check menu roles.
    |--------------------------------------------------------------------------
    */
    'check_menu_roles'       => true,

    /*
    |--------------------------------------------------------------------------
    | User default avatar
    |--------------------------------------------------------------------------
    |
    | Set a default avatar for newly created users.
    |
    */
    'default_avatar' => '/vendor/laravel-multi/MultiLTE/dist/img/user2-160x160.jpg',

    /*
    |--------------------------------------------------------------------------
    | Multi map field provider
    |--------------------------------------------------------------------------
    |
    | Supported: "tencent", "google", "yandex".
    |
    */
    'map_provider' => 'google',

    /*
    |--------------------------------------------------------------------------
    | Application Skin
    |--------------------------------------------------------------------------
    |
    | This value is the skin of multi pages.
    | @see https://multilte.io/docs/2.4/layout
    |
    | Supported:
    |    "skin-blue", "skin-blue-light", "skin-yellow", "skin-yellow-light",
    |    "skin-green", "skin-green-light", "skin-purple", "skin-purple-light",
    |    "skin-red", "skin-red-light", "skin-black", "skin-black-light".
    |
    */
    'skin' => env('MULTI_SKIN', 'skin-blue-light'),

    /*
    |--------------------------------------------------------------------------
    | Application layout
    |--------------------------------------------------------------------------
    |
    | This value is the layout of multi pages.
    | @see https://multilte.io/docs/2.4/layout
    |
    | Supported: "fixed", "layout-boxed", "layout-top-nav", "sidebar-collapse",
    | "sidebar-mini".
    |
    */
    'layout' => ['sidebar-mini', 'sidebar-collapse'],

    /*
    |--------------------------------------------------------------------------
    | Login page background image
    |--------------------------------------------------------------------------
    |
    | This value is used to set the background image of login page.
    |
    */
    'login_background_image' => '',

    /*
    |--------------------------------------------------------------------------
    | Show version at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the version number of laravel-multi at the footer of
    | each page
    |
    */
    'show_version' => true,

    /*
    |--------------------------------------------------------------------------
    | Show environment at footer
    |--------------------------------------------------------------------------
    |
    | Whether to display the environment at the footer of each page
    |
    */
    'show_environment' => true,

    /*
    |--------------------------------------------------------------------------
    | Menu bind to permission
    |--------------------------------------------------------------------------
    |
    | whether enable menu bind to a permission
    */
    'menu_bind_permission' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable default breadcrumb
    |--------------------------------------------------------------------------
    |
    | Whether enable default breadcrumb for every page content.
    */
    'enable_default_breadcrumb' => true,

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable assets minify
    |--------------------------------------------------------------------------
    */
    'minify_assets' => [

        // Assets will not be minified.
        'excepts' => [

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Enable/Disable sidebar menu search
    |--------------------------------------------------------------------------
    */
    'enable_menu_search' => true,

    /*
    |--------------------------------------------------------------------------
    | Exclude route from generate menu command
    |--------------------------------------------------------------------------
    */
    'menu_exclude' => [
        '_handle_selectable_',
        '_handle_renderable_',
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert message that will displayed on top of the page.
    |--------------------------------------------------------------------------
    */
    'top_alert' => '',

    /*
    |--------------------------------------------------------------------------
    | The global Grid action display class.
    |--------------------------------------------------------------------------
    */
    'grid_action_class' => \MicroEcology\Multi\Grid\Displayers\DropdownActions::class,

    /*
    |--------------------------------------------------------------------------
    | Extension Directory
    |--------------------------------------------------------------------------
    |
    | When you use command `php artisan multi:extend` to generate extensions,
    | the extension files will be generated in this directory.
    */
    'extension_dir' => app_path('Multi/Extensions'),

    /*
    |--------------------------------------------------------------------------
    | Settings for extensions.
    |--------------------------------------------------------------------------
    |
    | You can find all available extensions here
    | https://github.com/laravel-multi-extensions.
    |
    */
    'extensions' => [

    ],
];
