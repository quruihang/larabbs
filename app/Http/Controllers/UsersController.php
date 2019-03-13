<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;

class UsersController extends Controller
{
    // 个人页面的展示
    public function show(User $user)
    {
        /**
        Laravel 会自动解析定义在控制器方法（变量名匹配路由片段）中的 Eloquent 模型类型声明。在上面代码中，由于 show() 方法传参时声明了类型 —— Eloquent 模型 User，对应的变量名 $user 会匹配路由片段中的 {user}，这样，Laravel 会自动注入与请求 URI 中传入的 ID 对应的用户模型实例。
        此功能称为 『隐性路由模型绑定』，是『约定优于配置』设计范式的体现，同时满足以下两种情况，此功能即会自动启用：
        1). 路由声明时必须使用 Eloquent 模型的单数小写格式来作为 路由片段参数，User 对应 {user}:
            Route::get('/users/{user}', 'UsersController@show')->name('users.show');
        2). 控制器方法传参中必须包含对应的 Eloquent 模型类型 提示，并且是有序的：
         */
        return view('users.show', compact('user'));
        //将用户对象变量 $user 通过 compact 方法转化为一个关联数组，并作为第二个参数传递给 view 方法，将变量数据传递到视图中
    }

    // 编辑个人信息
    public function edit(User $user)
    {
        /**
        edit() 方法接受 $user 用户作为传参，也就是说当 URL 是 http://larabbs.test/users/1/edit 时，读取的是 ID 为 1 的用户。这里使用的是与 show() 方法一致的 『隐性路由模型绑定』 开发范式。
        view() 方法加载了 resources/views/users/edit.blade.php 模板，并将用户实例作为变量传置于模板中。
         */
        return view('users.edit', compact('user'));
    }

    // 保存个人信息
    public function update(UserRequest $request, User $user)
    {
        // 使用『表单请求验证』（FormRequest）来验证用户提交的数据。
        $user->update($request->all());
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }



}
