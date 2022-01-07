<?php

namespace Micro\Multi\Controllers;

use Micro\Multi\Form;
use Micro\Multi\Layout\Column;
use Micro\Multi\Layout\Content;
use Micro\Multi\Layout\Row;
use Micro\Multi\Tree;
use Micro\Multi\Widgets\Box;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    use HasResourceActions;

    /**
     * Index interface.
     *
     * @param Content $content
     *
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->title(trans('multi.menu'))
            ->description(trans('multi.list'))
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Micro\Multi\Widgets\Form();
                    $form->action(multi_url('auth/menu'));

                    $menuModel = config('multi.database.menu_model');
                    $permissionModel = config('multi.database.permissions_model');
                    $roleModel = config('multi.database.roles_model');

                    $form->select('parent_id', trans('multi.parent_id'))->options($menuModel::selectOptions());
                    $form->text('title', trans('multi.title'))->rules('required');
                    $form->icon('icon', trans('multi.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
                    $form->text('uri', trans('multi.uri'));
                    $form->multipleSelect('roles', trans('multi.roles'))->options($roleModel::all()->pluck('name', 'id'));
                    if ((new $menuModel())->withPermission()) {
                        $form->select('permission', trans('multi.permission'))->options($permissionModel::pluck('name', 'slug'));
                    }
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('multi.new'), $form))->style('success'));
                });
            });
    }

    /**
     * Redirect to edit page.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        return redirect()->route('multi.auth.menu.edit', ['menu' => $id]);
    }

    /**
     * @return \Micro\Multi\Tree
     */
    protected function treeView()
    {
        $menuModel = config('multi.database.menu_model');

        $tree = new Tree(new $menuModel());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['title']}</strong>";

            if (!isset($branch['children'])) {
                if (url()->isValidUrl($branch['uri'])) {
                    $uri = $branch['uri'];
                } else {
                    $uri = multi_url($branch['uri']);
                }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$uri\" class=\"dd-nodrag\">$uri</a>";
            }

            return $payload;
        });

        return $tree;
    }

    /**
     * Edit interface.
     *
     * @param string  $id
     * @param Content $content
     *
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->title(trans('multi.menu'))
            ->description(trans('multi.edit'))
            ->row($this->form()->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $menuModel = config('multi.database.menu_model');
        $permissionModel = config('multi.database.permissions_model');
        $roleModel = config('multi.database.roles_model');

        $form = new Form(new $menuModel());

        $form->display('id', 'ID');

        $form->select('parent_id', trans('multi.parent_id'))->options($menuModel::selectOptions());
        $form->text('title', trans('multi.title'))->rules('required');
        $form->icon('icon', trans('multi.icon'))->default('fa-bars')->rules('required')->help($this->iconHelp());
        $form->text('uri', trans('multi.uri'));
        $form->multipleSelect('roles', trans('multi.roles'))->options($roleModel::all()->pluck('name', 'id'));
        if ($form->model()->withPermission()) {
            $form->select('permission', trans('multi.permission'))->options($permissionModel::pluck('name', 'slug'));
        }

        $form->display('created_at', trans('multi.created_at'));
        $form->display('updated_at', trans('multi.updated_at'));

        return $form;
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
    }
}
