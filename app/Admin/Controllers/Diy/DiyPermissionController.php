<?php

namespace App\Admin\Controllers\Diy;


use App\Models\Diy\DiyPermissionsModel;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Controllers\PermissionController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Tree;
use Encore\Admin\Widgets\Box;

class DiyPermissionController extends PermissionController{

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
            ->title(trans('admin.permission'))
            ->description(trans('admin.list').'请勿随意更改标识')
            ->row(function (Row $row) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) {
                    $form = new \Encore\Admin\Widgets\Form();
                    $form->action(admin_url('auth/diy_permissions'));


                    $permissionModel = DiyPermissionsModel::class;


                    $form->select('parent_id', trans('父权限'))->options($permissionModel::selectOptions());
                    $form->text('slug', trans('admin.slug'))->rules('required');
                    $form->text('name', trans('admin.name'))->rules('required');

                    $form->multipleSelect('http_method', trans('admin.http.method'))
                        ->options($this->getHttpMethodsOptions())
                        ->help(trans('admin.all_methods_if_empty'));
                    $form->textarea('http_path', trans('admin.http.path'));


                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box(trans('admin.new'), $form))->style('success'));
                });
            });
    }


    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        $permissionModel = DiyPermissionsModel::class;

        $tree = new Tree(new $permissionModel());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<strong>{$branch['name']} </strong>";
            $payload.=$branch['slug'];

            $method_str = '&nbsp;&nbsp;&nbsp;&nbsp;';
            if(empty($branch['http_method'])){
                $method_str .= '<button class="btn btn-success" style="padding: 0" >any</button>';
            }else{
                foreach ($branch['http_method'] as $key=>$value){
                    $method_str .= "<button class='btn btn-info' style='padding: 0' >{$value}</button>";
                }

            }
            $payload.=$method_str;

//            $payload.= "<strong>{$branch['http_method']} </strong>";
            $payload.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$branch['http_path'];

            return $payload;
        });

        return $tree;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        $permissionModel = DiyPermissionsModel::class;

        $form = new Form(new $permissionModel());

        $form->display('id', 'ID');

        $form->select('parent_id', trans('父权限'))->options($permissionModel::selectOptions());
        $form->text('slug', trans('admin.slug'))->rules('required');
        $form->text('name', trans('admin.name'))->rules('required');

        $form->multipleSelect('http_method', trans('admin.http.method'))
            ->options($this->getHttpMethodsOptions())
            ->help(trans('admin.all_methods_if_empty'));
        $form->textarea('http_path', trans('admin.http.path'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }

}
