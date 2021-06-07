<?php


namespace App\Admin\Controllers\Diy;


use Encore\Admin\Auth\Database\OperationLog;
use Encore\Admin\Controllers\LogController;
use Encore\Admin\Controllers\RoleController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Illuminate\Support\Arr;

class DiyLogController extends LogController
{

    const METHOD_TYPE=[
        'GET'=>'查看/搜索',
        'POST'=>'操作',
        'PUT'=>'修改',
        'DELETE'=>'删除',
    ];


    /**
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OperationLog());

        $grid->model()->orderBy('id', 'DESC');

        $grid->column('id', 'ID')->sortable();
        $grid->column('user.name', '用户');
        $grid->column('method')->display(function ($method) {
            $color = Arr::get(OperationLog::$methodColors, $method, 'grey');

            $method = self::METHOD_TYPE[$method] ?? $method;
            return "<span class=\"badge bg-$color\">$method</span>";
        });
        $grid->column('path','操作功能')->display(function($path){
             $array = explode('/',$path);
             array_shift($array);
             if(count($array)>=2){
                 $ab_path = $array[0].'/'.$array[1];
             }else{
                 $ab_path = implode('/',$array);
             }
             $data= DiyLogController::description_api($ab_path,$this->input);

             return $data['name'].'<br>'.$path;

        });
        $grid->column('input','操作详细')->display(function ($input) {
            $input = json_decode($input, true);
            $input = Arr::except($input, ['_pjax', '_token', '_method', '_previous_']);
            if (empty($input)) {
                return '<code>{}</code>';
            }

            return '<pre>'.json_encode($input, JSON_PRETTY_PRINT | JSON_HEX_TAG).'</pre>';
        });


        $grid->column('ip')->label('primary');
        $grid->column('created_at', trans('admin.created_at'));

        $grid->disableActions();
        $grid->actions(function (Grid\Displayers\Actions $actions) {
            $actions->disableEdit();
            $actions->disableView();

        });

        $grid->disableCreateButton();

        $grid->filter(function (Grid\Filter $filter) {
            $filter->expand();
            $userModel = config('admin.database.users_model');

            $filter->equal('user_id', '用户')->select($userModel::all()->pluck('name', 'id'));
//            $filter->equal('method')->select(array_combine(OperationLog::$methods, OperationLog::$methods));

            $filter->equal('method','行为')->select(self::METHOD_TYPE);

            $filter->like('path');
            $filter->equal('ip');
        });

        return $grid;
    }

    public static function description_api($ab_path,$value)
    {
        $data=[
            'name'=>'',
            'detail'=>'',
        ];
//        'auth/diy_logs'=>'操作日志',
//        'auth/logs'=>'操作日志',
//        'auth/diy_menu'=>'后台菜单',
//        'auth/menu'=>'后台菜单',
//        'game_users'=>'游戏用户',
//        '_handle_action_' => '右边操作栏',

        switch ($ab_path){
            case 'auth/diy_logs':
            case 'auth/diy_logs':
                $data['name'] = '操作日志';
                break;
            case 'auth/diy_menu':
            case 'auth/menu':
                $data['name'] = '后台菜单';
                break;
            case 'auth/diy_permissions':
            case 'auth/permissions':
                $data['name'] = '后台权限';
                break;
            case 'auth/diy_roles':
            case 'auth/roles':
                $data['name'] = '后台角色';
                break;
            case 'auth/users':
            case 'auth/diy_users':
                $data['name'] = '后台用户';
                break;
            case '':
                $data['name'] = '首页';
                break;
            case 'temp_recharge':
                $data['name'] = '充值订单交互数据';
                break;
            case 'temp_rchrg_api/notify':
                $data['name'] = '充值订单通知服务端交互';
                break;
            case 'temp_rchrg_api/refresh_pay_data':
                $data['name'] = '充值订单同步上游交互';
                break;
            case 'recharges':
                $data['name'] = '充值订单';
                break;
            case 'game_agent_users':
                $data['name'] = '分享用户';
                break;
            case 'game_coin_history':
                $data['name'] = '金币历史';
                break;
            case 'game_coin_history':
                $data['name'] = '金币流水';
                break;
            case 'game_history':
                $data['name'] = '游戏记录';
                break;
            case 'game_users':
                $data['name'] = '游戏用户';
                break;
            case '_handle_action_':
                $data['name'] = '右边操作栏';
                break;
            case 'game_active':
                $data['name'] = '活跃用户';
                break;
            case 'game_report_user':
                $data['name'] = '游戏用户统计';
                break;
            case 'game_share_usertree/change':
                $data['name'] = '代理线修改';
                break;
            default:
                break;

        }
        return $data;


    }
}