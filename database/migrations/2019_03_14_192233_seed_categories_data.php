<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SeedCategoriesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 使用 数据迁移功能（migration）初始化项目数据
        // 数据迁移也是项目的一部分，执行的时机刚好是在项目安装时。
        // 并且区分执行先后顺序，这确保了初始化数据发生在数据表结构创建完成后。
        // 定义命名规范为 seed_(数据库表名称)_data : php artisan make:migration seed_categories_data
        $categories = [
            [
                'name'        => '分享',
                'description' => '分享创造，分享发现',
            ],
            [
                'name'        => '教程',
                'description' => '开发技巧、推荐扩展包等',
            ],
            [
                'name'        => '问答',
                'description' => '请保持友善，互帮互助',
            ],
            [
                'name'        => '公告',
                'description' => '站点公告',
            ],
        ];

        DB::table('categories')->insert($categories);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('categories')->truncate();
    }
}
