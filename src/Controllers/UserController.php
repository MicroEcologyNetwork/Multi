<?php

namespace Micro\Multi\Controllers;

use Micro\Multi\Form;
use Micro\Multi\Grid;
use Micro\Multi\Show;
use Illuminate\Support\Facades\Hash;

class UserController extends MultiController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('multi.multiistrator');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $userModel = config('multi.database.users_model');

        $grid = new Grid(new $userModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('username', trans('multi.username'));
        $grid->column('name', trans('multi.name'));
        $grid->column('roles', trans('multi.roles'))->pluck('name')->label();
        $grid->column('created_at', trans('multi.created_at'));
        $grid->column('updated_at', trans('multi.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->getKey() == 1) {
                $actions->disableDelete();
            }
        });

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
        $userModel = config('multi.database.users_model');

        $show = new Show($userModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('username', trans('multi.username'));
        $show->field('name', trans('multi.name'));
        $show->field('roles', trans('multi.roles'))->as(function ($roles) {
            return $roles->pluck('name');
        })->label();
        $show->field('permissions', trans('multi.permissions'))->as(function ($permission) {
            return $permission->pluck('name');
        })->label();
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
        $userModel = config('multi.database.users_model');
        $permissionModel = config('multi.database.permissions_model');
        $roleModel = config('multi.database.roles_model');

        $form = new Form(new $userModel());

        $userTable = config('multi.database.users_table');
        $connection = config('multi.database.connection');

        $form->display('id', 'ID');
        $form->text('username', trans('multi.username'))
            ->creationRules(['required', "unique:{$connection}.{$userTable}"])
            ->updateRules(['required', "unique:{$connection}.{$userTable},username,{{id}}"]);

        $form->text('name', trans('multi.name'))->rules('required');
        $form->image('avatar', trans('multi.avatar'));
        $form->password('password', trans('multi.password'))->rules('required|confirmed');
        $form->password('password_confirmation', trans('multi.password_confirmation'))->rules('required')
            ->default(function ($form) {
                return $form->model()->password;
            });

        $form->ignore(['password_confirmation']);

        $form->multipleSelect('roles', trans('multi.roles'))->options($roleModel::all()->pluck('name', 'id'));
        $form->multipleSelect('permissions', trans('multi.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('multi.created_at'));
        $form->display('updated_at', trans('multi.updated_at'));

        $form->saving(function (Form $form) {
            if ($form->password && $form->model()->password != $form->password) {
                $form->password = Hash::make($form->password);
            }
        });

        return $form;
    }
}
