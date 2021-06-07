<?php


namespace App\Admin\Controllers\Diy;


use Encore\Admin\Controllers\RoleController;
use Encore\Admin\Form;
use Encore\Admin\Grid;

class DiyRoleController extends RoleController
{

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $roleModel = config('admin.database.roles_model');

        $grid = new Grid(new $roleModel());

        $grid->column('id', 'ID')->sortable();
        $grid->column('slug', trans('admin.slug'));
        $grid->column('name', trans('admin.name'));

//        $grid->column('permissions', trans('admin.permission'))->pluck('name')->label();

        $grid->column('created_at', trans('admin.created_at'));
        $grid->column('updated_at', trans('admin.updated_at'));

        $grid->actions(function (Grid\Displayers\Actions $actions) {
            if ($actions->row->slug == 'administrator') {
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
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $permissionModel = config('admin.database.permissions_model');
        $roleModel = config('admin.database.roles_model');

        $form = new Form(new $roleModel());

        $form->display('id', 'ID');

        $form->text('slug', trans('admin.slug'))->rules('required');
        $form->text('name', trans('admin.name'))->rules('required');
//        $form->listbox('permissions', trans('admin.permissions'))->options($permissionModel::all()->pluck('name', 'id'));

        $first = $permissionModel::where('parent_id',0)->orderBy('order','asc')->get();
        foreach ($first as $key=>$value){

           $second = $permissionModel::where('parent_id',$value->id)->orderBy('order','asc')->get()->pluck('name', 'id')->toArray();

           $form->checkbox('permissions',$value->name)->options([
              $value->id => $value->name,
           ]+$second);

        }


        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        $form->saving(function($form){

//            dump($form);die;
        });

        return $form;
    }
}