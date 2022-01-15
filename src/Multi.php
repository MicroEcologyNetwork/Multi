<?php

namespace MicroEcology\Multi;

use Closure;
use MicroEcology\Multi\Auth\Database\Menu;
use MicroEcology\Multi\Controllers\AuthController;
use MicroEcology\Multi\Layout\Content;
use MicroEcology\Multi\Traits\HasAssets;
use MicroEcology\Multi\Widgets\Navbar;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;

/**
 * Class Multi.
 */
class Multi
{
    use HasAssets;

    /**
     * The Laravel multi version.
     *
     * @var string
     */
    const VERSION = '1.8.14';

    /**
     * @var Navbar
     */
    protected $navbar;

    /**
     * @var array
     */
    protected $menu = [];

    /**
     * @var string
     */
    public static $metaTitle;

    /**
     * @var string
     */
    public static $favicon;

    /**
     * @var array
     */
    public static $extensions = [];

    /**
     * @var []Closure
     */
    protected static $bootingCallbacks = [];

    /**
     * @var []Closure
     */
    protected static $bootedCallbacks = [];

    /**
     * Returns the long version of Laravel-multi.
     *
     * @return string The long application version
     */
    public static function getLongVersion()
    {
        return sprintf('Laravel-multi <comment>version</comment> <info>%s</info>', self::VERSION);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return \MicroEcology\Multi\Grid
     *
     * @deprecated since v1.6.1
     */
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return \MicroEcology\Multi\Form
     *
     *  @deprecated since v1.6.1
     */
    public function form($model, Closure $callable)
    {
        return new Form($this->getModel($model), $callable);
    }

    /**
     * Build a tree.
     *
     * @param $model
     * @param Closure|null $callable
     *
     * @return \MicroEcology\Multi\Tree
     */
    public function tree($model, Closure $callable = null)
    {
        return new Tree($this->getModel($model), $callable);
    }

    /**
     * Build show page.
     *
     * @param $model
     * @param mixed $callable
     *
     * @return Show
     *
     * @deprecated since v1.6.1
     */
    public function show($model, $callable = null)
    {
        return new Show($this->getModel($model), $callable);
    }

    /**
     * @param Closure $callable
     *
     * @return \MicroEcology\Multi\Layout\Content
     *
     * @deprecated since v1.6.1
     */
    public function content(Closure $callable = null)
    {
        return new Content($callable);
    }

    /**
     * @param $model
     *
     * @return mixed
     */
    public function getModel($model)
    {
        if ($model instanceof Model) {
            return $model;
        }

        if (is_string($model) && class_exists($model)) {
            return $this->getModel(new $model());
        }

        throw new InvalidArgumentException("$model is not a valid model");
    }

    /**
     * Left sider-bar menu.
     *
     * @return array
     */
    public function menu()
    {
        if (!empty($this->menu)) {
            return $this->menu;
        }

        $menuClass = config('multi.database.menu_model');

        /** @var Menu $menuModel */
        $menuModel = new $menuClass();

        return $this->menu = $menuModel->toTree();
    }

    /**
     * @param array $menu
     *
     * @return array
     */
    public function menuLinks($menu = [])
    {
        if (empty($menu)) {
            $menu = $this->menu();
        }

        $links = [];

        foreach ($menu as $item) {
            if (!empty($item['children'])) {
                $links = array_merge($links, $this->menuLinks($item['children']));
            } else {
                $links[] = Arr::only($item, ['title', 'uri', 'icon']);
            }
        }

        return $links;
    }

    /**
     * Set multi title.
     *
     * @param string $title
     *
     * @return void
     */
    public static function setTitle($title)
    {
        self::$metaTitle = $title;
    }

    /**
     * Get multi title.
     *
     * @return string
     */
    public function title()
    {
        return self::$metaTitle ? self::$metaTitle : config('multi.title');
    }

    /**
     * @param null|string $favicon
     *
     * @return string|void
     */
    public function favicon($favicon = null)
    {
        if (is_null($favicon)) {
            return static::$favicon;
        }

        static::$favicon = $favicon;
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        return $this->guard()->user();
    }

    /**
     * Attempt to get the guard from the local cache.
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard()
    {
        $guard = config('multi.auth.guard') ?: 'multi';

        return Auth::guard($guard);
    }

    /**
     * Set navbar.
     *
     * @param Closure|null $builder
     *
     * @return Navbar
     */
    public function navbar(Closure $builder = null)
    {
        if (is_null($builder)) {
            return $this->getNavbar();
        }

        call_user_func($builder, $this->getNavbar());
    }

    /**
     * Get navbar object.
     *
     * @return \MicroEcology\Multi\Widgets\Navbar
     */
    public function getNavbar()
    {
        if (is_null($this->navbar)) {
            $this->navbar = new Navbar();
        }

        return $this->navbar;
    }

    /**
     * Register the laravel-multi builtin routes.
     *
     * @return void
     *
     * @deprecated Use Multi::routes() instead();
     */
    public function registerAuthRoutes()
    {
        $this->routes();
    }

    /**
     * Register the laravel-multi builtin routes.
     *
     * @return void
     */
    public function routes()
    {
        $attributes = [
            'prefix'     => config('multi.route.prefix'),
            'middleware' => config('multi.route.middleware'),
        ];

        app('router')->group($attributes, function ($router) {

            /* @var \Illuminate\Support\Facades\Route $router */
            $router->namespace('\MicroEcology\Multi\Controllers')->group(function ($router) {

                /* @var \Illuminate\Routing\Router $router */
                $router->resource('auth/users', 'UserController')->names('multi.auth.users');
                $router->resource('auth/roles', 'RoleController')->names('multi.auth.roles');
                $router->resource('auth/permissions', 'PermissionController')->names('multi.auth.permissions');
                if(config('multi.multi_limit.menu_module')){
                    $router->resource('auth/menu', 'MenuController', ['except' => ['create']])->names('multi.auth.menu');
                }
                $router->resource('auth/logs', 'LogController', ['only' => ['index', 'destroy']])->names('multi.auth.logs');

                $router->post('_handle_form_', 'HandleController@handleForm')->name('multi.handle-form');
                $router->post('_handle_action_', 'HandleController@handleAction')->name('multi.handle-action');
                $router->get('_handle_selectable_', 'HandleController@handleSelectable')->name('multi.handle-selectable');
                $router->get('_handle_renderable_', 'HandleController@handleRenderable')->name('multi.handle-renderable');
            });

            $authController = config('multi.auth.controller', AuthController::class);

            /* @var \Illuminate\Routing\Router $router */
            $router->get('auth/login', $authController.'@getLogin')->name('multi.login');
            $router->post('auth/login', $authController.'@postLogin');
            $router->get('auth/logout', $authController.'@getLogout')->name('multi.logout');
            $router->get('auth/setting', $authController.'@getSetting')->name('multi.setting');
            $router->put('auth/setting', $authController.'@putSetting');
        });
    }

    /**
     * Extend a extension.
     *
     * @param string $name
     * @param string $class
     *
     * @return void
     */
    public static function extend($name, $class)
    {
        static::$extensions[$name] = $class;
    }

    /**
     * @param callable $callback
     */
    public static function booting(callable $callback)
    {
        static::$bootingCallbacks[] = $callback;
    }

    /**
     * @param callable $callback
     */
    public static function booted(callable $callback)
    {
        static::$bootedCallbacks[] = $callback;
    }

    /**
     * Bootstrap the multi application.
     */
    public function bootstrap()
    {
        $this->fireBootingCallbacks();

        require config('multi.bootstrap', multi_path('bootstrap.php'));

        $this->addMultiAssets();

        $this->fireBootedCallbacks();
    }

    /**
     * Add JS & CSS assets to pages.
     */
    protected function addMultiAssets()
    {
        $assets = Form::collectFieldAssets();

        self::css($assets['css']);
        self::js($assets['js']);
    }

    /**
     * Call the booting callbacks for the multi application.
     */
    protected function fireBootingCallbacks()
    {
        foreach (static::$bootingCallbacks as $callable) {
            call_user_func($callable);
        }
    }

    /**
     * Call the booted callbacks for the multi application.
     */
    protected function fireBootedCallbacks()
    {
        foreach (static::$bootedCallbacks as $callable) {
            call_user_func($callable);
        }
    }

    /*
     * Disable Pjax for current Request
     *
     * @return void
     */
    public function disablePjax()
    {
        if (request()->pjax()) {
            request()->headers->set('X-PJAX', false);
        }
    }
}
