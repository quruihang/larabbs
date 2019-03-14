<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'user_id', 'category_id', 'reply_count', 'view_count', 'last_reply_user_id', 'order', 'excerpt', 'slug'];

    // 新增 category 和 user 的模型关联
    public function category()
    {
        // 一个话题属于一个分类；
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        // 一个话题拥有一个作者。
        return $this->belongsTo(User::class);
    }
}
