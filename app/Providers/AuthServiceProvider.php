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

        //
    }
}
