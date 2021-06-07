<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {


//        $router->group(['middleware'=> \App\Http\Middleware\WhiteIp::class],function(Router $router){

            $router->resource('auth/diy_roles', 'Diy\DiyRoleController')->names('admin.auth.diy_roles');
            $router->resource('auth/diy_menu', 'Diy\DiyMenuController')->names('admin.auth.diy_menu');
            $router->resource('auth/diy_permissions', 'Diy\DiyPermissionController')->names('admin.auth.diy_permissions');
            $router->resource('auth/diy_logs', 'Diy\DiyLogController')->names('admin.auth.diy_logs');

            $router->get('/', 'HomeController@index')->name('home');

//        });

    });
