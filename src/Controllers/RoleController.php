<?php

namespace MicroEcology\Multi\Controllers;

use MicroEcology\Multi\Form;
use MicroEcology\Multi\Grid;
use MicroEcology\Multi\Show;

class RoleController extends MultiController
{
    /**
     * {@inheritdoc}
     */
    protected function title()
    {
        return trans('multi.roles');
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $roleModel = config('multi.database.roles_model');

        $grid = new Grid(new $roleModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('slug', trans('multi.slug'));
        $grid->column('name', trans('multi.name'));

        $grid->column('permissions', trans('multi.permission'))->pluck('name')->label();

        $grid->column('created_at', trans('multi.created_at'));
        $grid->column('updated_at', trans('multi.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->row->slug == 'multiistrator') {
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
        $roleModel = config('multi.database.roles_model');

        $show = new Show($roleModel::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('slug', trans('multi.slug'));
        $show->field('name', trans('multi.name'));
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
        $permissionModel = config('multi.database.permissions_model');
        $roleModel = config('multi.database.roles_model');

        $form = new Form(new $roleModel());

        $form->display('id', 'ID');

        $form->text('slug', trans('multi.slug'))->rules('required');
        $form->text('name', trans('multi.name'))->rules('required');
        $form->listbox('permissions', trans('multi.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $form->display('created_at', trans('multi.created_at'));
        $form->display('updated_at', trans('multi.updated_at'));

        return $form;
    }
}
