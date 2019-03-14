<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Handlers\ImageUploadHandler;

class UsersController extends Controller
{

    public function __construct()
    {
        // Laravel 提供的 Auth 中间件在过滤指定动作时，如该用户未通过身份验证（未登录用户），将会被重定向到登录页面
        // 使用身份验证（Auth）中间件来过滤未登录用户的 edit, update 动作
        // 在 __construct 方法中调用了 middleware 方法，该方法接收两个参数，第一个为中间件的名称，第二个为要进行过滤的动作。
        // 通过 except 方法来设定 指定动作 不使用 Auth 中间件进行过滤，意为 —— 除了此处指定的动作以外，所有其他动作都必须登录用户才能访问，类似于黑名单的过滤机制。
        // 相反的还有 only 白名单方法，将只过滤指定动作。我们提倡在控制器 Auth 中间件使用中，首选 except 方法，这样的话，当新增一个控制器方法时，默认是安全的，此为最佳实践。
        $this->middleware('auth', ['except' => ['show']]);
    }

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
        $this->authorize('update', $user);
        /**
        edit() 方法接受 $user 用户作为传参，也就是说当 URL 是 http://larabbs.test/users/1/edit 时，读取的是 ID 为 1 的用户。这里使用的是与 show() 方法一致的 『隐性路由模型绑定』 开发范式。
        view() 方法加载了 resources/views/users/edit.blade.php 模板，并将用户实例作为变量传置于模板中。
         */
        return view('users.edit', compact('user'));
    }

    // 保存个人信息
    public function update(UserRequest $request, ImageUploadHandler $uploader, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->all();
        // $data = $request->all(); 赋值 $data 变量，以便对更新数据的操作；

        if ($request->avatar) {
            $result = $uploader->save($request->avatar, 'avatars', $user->id, 362);
            if ($result) {
                // if ($result) 的判断是因为 ImageUploadHandler 对文件后缀名做了限定，不允许的情况下将返回 false
                $data['avatar'] = $result['path'];
            }
        }

        // 使用『表单请求验证』（FormRequest）来验证用户提交的数据。
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }



}
