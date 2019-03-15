<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
//    use Notifiable;
    use Notifiable {
        // 给 notify方法 一个改变了访问控制的别名
        // 原版 notify 的访问控制则没有发生变化
        notify as protected laravelNotify;
    }

    // 重写 notify() 方法，每当调用 $user->notify() 时， users 表里的 notification_count 将自动 +1。
    public function notify($instance)
    {
        // 如果要通知的人是当前用户，就不必通知了！
        if ($this->id == Auth::id()) {
            return;
        }
        $this->increment('notification_count');
        $this->laravelNotify($instance);
    }

    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','introduction','avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // 建立关联模型
    //用户与话题中间的关系是 一对多 的关系，一个用户拥有多个主题，在 Eloquent 中使用 hasMany() 方法进行关联。
    //关联设置成功后，即可使用 $user->topics 来获取到用户发布的所有话题数据。
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        // 一个用户可以拥有多条评论
        return $this->hasMany(Reply::class);
    }

    // 验证是否是本人
    public function isAuthorOf($model)
    {
        return $this->id == $model->user_id;
    }
}
