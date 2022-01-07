# 常见问题汇总

## 重写内置视图

如果有需要自己修改view，但是不方便直接修改`laravel-multi`的情况，可以用下面的办法解决

复制`vendor/encore/laravel-multi/views`到项目的`resources/views/multi`，然后在`app/Multi/bootstrap.php`文件中加入代码：

```php
app('view')->prependNamespace('multi', resource_path('views/multi'));
```

这样就用`resources/views/multi`下的视图覆盖了`laravel-multi`的视图，要注意的问题是，更新`laravel-multi`的时候，如果遇到视图方面的问题，需要重新复制`vendor/encore/laravel-multi/views`到项目的`resources/views/multi`中，注意备份原来已经修改过的视图。

## 设置语言

完成安装之后，默认语言为英文(en)，如果要使用中文，打开`config/app.php`，将`locale`设置为`zh-CN`即可。

## 关于扩展自定义组件

`laravel-multi`默认引用了大量前端资源，如果有网络问题或者有不需要使用的组件，可以参考[form组件管理](/zh/model-form-field-management.md)将其移除。

关于富文本编辑器，由于静态资源包文件普遍太大，所以`laravel-multi`默认通过cdn的方式引用`ckeditor`，建议大家根据自己的需求扩展编辑器，自行配置。

## 关于前端资源问题

如果需要使用自己的前端文件，可以在`app/Multi/bootstrap.php`中引入：

```php
Multi::css('path/to/your/css');
Multi::css('path/to/your/js');
```


## 重写登陆页面和登陆逻辑

在路由文件`app/Multi/routes.php`中，覆盖掉登陆页面和登陆逻辑的路由，即可实现自定义的功能

```php
Route::group([
    'prefix'        => config('multi.prefix'),
    'namespace'     => Multi::controllerNamespace(),
    'middleware'    => ['web', 'multi'],
], function (Router $router) {

    $router->get('auth/login', 'AuthController@getLogin');
    $router->post('auth/login', 'AuthController@postLogin');
    
});

```

在自定义的路由器AuthController中的`getLogin`、`postLogin`方法里分别实现自己的登陆页面和登陆逻辑。

参考控制器文件[AuthController.php](https://github.com/z-song/laravel-multi/blob/master/src/Controllers/AuthController.php)，视图文件[login.blade.php](https://github.com/z-song/laravel-multi/blob/master/views/login.blade.php)

## 更新静态资源

如果遇到更新之后,部分组件不能正常使用,那有可能是`laravel-multi`自带的静态资源有更新了,需要运行命令`php artisan vendor:publish --tag=laravel-multi-assets --force`来重新发布前端资源，发布之后不要忘记清理浏览器缓存.