<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    // 在 AuthServiceProvider 类中对授权策略进行注册。
    // AuthServiceProvider 包含了一个 policies 属性，该属性用于将各种模型对应到管理它们的授权策略上。
    // 为用户模型 User 指定授权策略 UserPolicy。
    protected $policies = [
		 \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
		 \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        'App\Model' => 'App\Policies\ModelPolicy',
        \App\Models\User::class  => \App\Policies\UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        /*
         * Horizon 控制面板页面的路由是 /horizon ，默认只能在 local 环境中访问仪表盘。
         * 使用 Horizon::auth 方法定义更具体的访问策略。auth 方法能够接受一个回调函数，
         * 此回调函数需要返回 true 或 false ，从而确认当前用户是否有权限访问 Horizon 仪表盘。
         * 定义 /horizon 的访问权限，只有 站长 才有权限查看：
         */
        \Horizon::auth(function ($request) {
            // 是否是站长
            return \Auth::user()->hasRole('Founder');
        });
    }
}
