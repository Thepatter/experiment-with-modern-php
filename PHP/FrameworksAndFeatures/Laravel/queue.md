### Laravel 队列任务

文档：https://laravel-china.org/docs/laravel/5.6/queues#6ef95f

队列允许异步执行消耗时间的任务，laravel 中使用队列的流程为
* 配置队列
* 生成任务类
* 任务分发

#### 配置队列
config/queue.php 文件，使用 redis 则需要安装依赖 `composer required "predis/predis:~1.0"`

创建失败任务表迁移 `php artisan queue:failed-table`

#### 生成任务类
`php artisan make:job TranslateSlug`

app/Jobs/TranslateSlug.php
```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\Topic;
use App\Handlers\SlugTranslateHandler;

class TranslateSlug implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $topic;

    public function __construct(Topic $topic)
    {
        // 队列任务构造器中接收了 Eloquent 模型，将会只序列化模型的 ID
        $this->topic = $topic;
    }

    public function handle()
    {
        // 请求百度 API 接口进行翻译
        $slug = app(SlugTranslateHandler::class)->translate($this->topic->title);

        // 为了避免模型监控器死循环调用，我们使用 DB 类直接对数据库进行操作
        \DB::table('topics')->where('id', $this->topic->id)->update(['slug' => $slug]);
    }
}
```

该类实现了 `Illminate\Contracts\Queue\ShouldQueue` 接口，该接口表明 laravel 应该将该任务添加到后台的任务队列中，而不是同步执行

引入了 `SerializeModels` trait，如果队列任务类在构造器中接收了一个 Eloquent 模型，那么只有可识别该模型的属性会被序列化到队列里，当任务被实际运行时，队列系统便会自动从数据库中重新取回完整的模型。

handle 方法会在队列任务执行时被调用，handle 方法可以使用类型提示来进行依赖注入

如果在模型监控器中分发任务，任务中要避免使用 Eloquent 模型接口调用，如：create(), update(), save() 等操作，否则会陷入调用死循环
模型监控器分发任务，任务触发模型监控器，模型监控器再次分发任务，任务再次触发模型监控器的死循环，在模型监控器中分发任务，使用 **DB** 类直接操作数据库

#### 任务分发
```php
<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        // XSS 过滤
        $topic->body = clean($topic->body, 'user_topic_body');

        // 生成话题摘录
        $topic->excerpt = make_excerpt($topic->body);

        // 如 slug 字段无内容，即使用翻译器对 title 进行翻译
        if ( ! $topic->slug) {

            // 推送任务到队列
            dispatch(new TranslateSlug($topic));
        }
    }
}
```

#### 使用
在命令行中启动队列系统 `php artisan queue:listen`

可视化队列监控 `Horizon`

* 安装 `composer require "laravel/horizon:~1.0"`
* 发布配置文件 `php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"`
  会生成配置文件 `config/horizon.php` 和存放在 `public/vendor/horizon` 文件夹中的 CSS, JS 等资源文件
* 浏览器打开 `/horizon`

* `php artisan horizon` 启动队列与任务监控。

