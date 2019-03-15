<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    // 在 Topic 模型保存时触发的 saving 事件中，对 excerpt 字段进行赋值
    public function saving(Topic $topic)
    {
        // 在数据入库前进行过滤
        $topic->body = clean($topic->body, 'user_topic_body');
        // make_excerpt() 是自定义的辅助方法，需要在 helpers.php 文件中添加
        $topic->excerpt = make_excerpt($topic->body);
    }
}