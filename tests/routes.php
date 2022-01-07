<?php

Route::group([
    'prefix'     => config('multi.route.prefix'),
    'namespace'  => 'Tests\Controllers',
    'middleware' => ['web', 'multi'],
], function ($router) {
    $router->resource('images', ImageController::class);
    $router->resource('multiple-images', MultipleImageController::class);
    $router->resource('files', FileController::class);
    $router->resource('users', UserController::class);
});
