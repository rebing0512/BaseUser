<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('mbuser_users')) {
            Schema::create('mbuser_users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('username')->nullable();
                $table->string('phone')->unique()->nullable();
                $table->string('email')->unique()->nullable();
                $table->string('password',60)->nullable();
                $table->string('fullName')->nullable();
                $table->string('roles')->nullable();
                $table->dateTime('last_login_time')->nullable();
                $table->string('last_login_ip')->nullable();
                $table->tinyInteger('status')->default(1)->comment("1-正常，2-禁用");
                $table->tinyInteger('confirm_email')->default(2)->comment('1-验证，2-没验证');
                $table->tinyInteger('register_method')->default(2)->comment('1-用户注册（默认），2-后台添加');
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mbuser_users');
    }
}
