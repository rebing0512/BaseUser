<?php

namespace Jenson\BaseUser;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Schema;
use Blade;

// 任务
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;

use Jenson\BaseUser\Console\Commands\Command;

class ServiceProvider extends BaseServiceProvider
{

    public function boot()
    {

        // 特殊字段太长报错
        Schema::defaultStringLength(191);

        // 模板机制中使用的量
        Blade::directive('getLinkUrl', function($expression) {
            return "<?php echo Route($expression); ?>";
        });


        // 【1】模板
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'mbcore.baseuser');
        //发布视图到resources/views/vendor目录
        $this->publishes([
            __DIR__ . '/resources/views' => resource_path('/views/Jenson/BaseUser'),
        ]);

        // 【2】路由
        $this->setupRoutes($this->app->router);

        // 【3】配置
        $this->mergeConfigFrom(
            __DIR__ . '/config/mbcore_baseuser.php', 'mbcore_baseuser'
        );
        //发布配置文件
        $this->publishes([
            __DIR__.'/config/mbcore_baseuser.php' => config_path('mbcore_baseuser.php'),
        ], 'config');

        // 【4】数据库迁移
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // 【5】资源文件
        $this->publishes([
            __DIR__.'/resources/assets' => public_path('assets/Jenson/BaseUser'),
        ], 'public');

        // 【6】注册 Artisan 命令
        if ($this->app->runningInConsole()) {
            $this->commands([
                Command::class,
            ]);
        }

        // 【任务队列】
        Queue::before(function (JobProcessing $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
//            \Log::notice("Queue::MBCore/BaseUser/before");
        });

        Queue::after(function (JobProcessed $event) {
            // $event->connectionName
            // $event->job
            // $event->job->payload()
//            \Log::notice("Queue::MBCore/BaseUser/after");
        });

        //  任务失败事件
        Queue::failing(function (JobFailed $event) {
            // $event->connectionName
            // $event->job
            // $event->exception;
//            \Log::notice("Queue::MBCore/BaseUser/failing");
        });
        // 在进程尝试从队列中获取任务之前指定要执行的回调
        ///*
        Queue::looping(function () {
            //事务级别
            //while (\DB::transactionLevel() > 0) {
            //回滚
            //    \DB::rollBack();
            //}
//            \Log::notice("Queue::MBCore/BaseUser/looping");
        });


    }

    public function setupRoutes(Router $router)
    {
        $router->group(['namespace' => 'Jenson\BaseUser\Controllers'], function($router)
        {
            require __DIR__ . '/routes/routes.php';
        });
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        config([
            'config/mbcore_baseuser.php',
        ]);
    }
}
