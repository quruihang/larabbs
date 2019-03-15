<?php

namespace App\Observers;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

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
        // XSS 过滤
        // 在数据入库前进行过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        // 生成话题摘录
        // make_excerpt() 是自定义的辅助方法，需要在 helpers.php 文件中添加
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {
            // app() 允许我们使用 Laravel 服务容器 ，此处我们用来生成 SlugTranslateHandler 实例
            $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        }
    }
}