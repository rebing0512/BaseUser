<?php

namespace Jenson\BaseUser\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    // 软删除
    use SoftDeletes;

    protected $table = 'mbuser_users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected $fillable = [
//        'username', 'password', 'fullName','roles ','email ','created_at','updated_at'
//    ];

    // 字段
    public  $fillable = [
        'username','email','password','fullName','roles','last_login_time','last_login_ip','status','confirm_email','remember_token',
        'phone','register_method'
    ];

    // 守卫
    protected $guarded = [
        'remember_token','last_login_time ','last_login_ip '
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
