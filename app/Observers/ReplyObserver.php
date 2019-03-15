<?php

namespace App\Observers;

use App\Models\Reply;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class ReplyObserver
{
    public function creating(Reply $reply)
    {
        // 过滤
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    public function updating(Reply $reply)
    {
        //
    }

    // 监控 created 事件，当 Elequont 模型数据成功创建时，created 方法将会被调用。
    public function created(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1);
    }
}