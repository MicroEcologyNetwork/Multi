<?php

namespace Micro\Multi\Controllers;

use Micro\Multi\Form;
use Micro\Multi\Grid;
use Micro\Multi\Show;
use Illuminate\Support\Str;

class PermissionController extends MultiController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('multi.permissions');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $permissionModel = config('multi.database.permissions_model');

        $grid = new Grid(new $permissionModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('slug', trans('multi.slug'));
        $grid->column('name', trans('multi.name'));

        $grid->column('http_path', trans('multi.route'))->display(function ($path) {
            return collect(explode("\n", $path))->map(function ($path) {
                $method = $this->http_method ?: ['ANY'];

                if (Str::contains($path, ':')) {
                    list($method, $path) = explode(':', $path);
                    $method = explode(',', $method);
                }

                $method = collect($method)->map(function ($name) {
                    return strtoupper($name);
                })->map(function ($name) {
                    return "<span class='label label-primary'>{$name}</span>";
                })->implode('&nbsp;');

                if (!empty(config('multi.route.prefix'))) {
                    $path = '/'.trim(config('multi.route.prefix'), '/').$path;
                }

                return "<div style='margin-bottom: 5px;'>$method<code>$path</code></div>";
            })->implode('');
        });

        $grid->column('created_at', trans('multi.created_at'));
        $grid->column('updated_at', trans('multi.updated_at'));

        $grid->tools(function (Grid\Tools $tools) {
            $tools->batch(function (Grid\Tools\BatchActions $actions) {
                $actions->disableDelete();
            });
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        $permissionModel = config('multi.database.permissions_model');

        $show = new Show($permissionModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('slug', trans('multi.slug'));
        $show->field('name', trans('multi.name'));

        $show->field('http_path', trans('multi.route'))->unescape()->as(function ($path) {
            return collect(explode("\r\n", $path))->map(function ($path) {
                $method = $this->http_method ?: ['ANY'];

                if (Str::contains($path, ':')) {
                    list($method, $path) = explode(':', $path);
                    $method = explode(',', $method);
                }

                $method = collect($method)->map(function ($name) {
                    return strtoupper($name);
                })->map(function ($name) {
                    return "<span class='label label-primary'>{$name}</span>";
                })->implode('&nbsp;');

                if (!empty(config('multi.route.prefix'))) {
                    $path = '/'.trim(config('multi.route.prefix'), '/').$path;
                }

                return "<div style='margin-bottom: 5px;'>$method<code>$path</code></div>";
            })->implode('');
        });

        $show->field('created_at', trans('multi.created_at'));
        $show->field('updated_at', trans('multi.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $permissionModel = config('multi.database.permissions_model');

        $form = new Form(new $permissionModel());

        $form->display('id', 'ID');

        $form->text('slug', trans('multi.slug'))->rules('required');
        $form->text('name', trans('multi.name'))->rules('required');

        $form->multipleSelect('http_method', trans('multi.http.method'))
            ->options($this->getHttpMethodsOptions())
            ->help(trans('multi.all_methods_if_empty'));
        $form->textarea('http_path', trans('multi.http.path'));

        $form->display('created_at', trans('multi.created_at'));
        $form->display('updated_at', trans('multi.updated_at'));

        return $form;
    }

    /**
     * Get options of HTTP methods select field.
     *
     * @return array
     */
    protected function getHttpMethodsOptions()
    {
        $model = config('multi.database.permissions_model');

        return array_combine($model::$httpMethods, $model::$httpMethods);
    }
}
