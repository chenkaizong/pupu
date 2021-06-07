<?php

namespace App\Models\Diy;

use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Database\Eloquent\Model;


class DiyAdminUsersModel extends Model
{
    protected $table = 'admin_users';

    protected $primaryKey = 'id';

    protected $connection = "agent_admin_mysql";

    protected $fillable = ['username', 'password', 'name', 'avatar','player_id'];



}
