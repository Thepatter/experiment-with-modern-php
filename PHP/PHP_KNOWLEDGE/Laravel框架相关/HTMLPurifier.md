HTMLPurifier 
### 运用白名单机制与 html 文本信息进行过滤
文档：http://htmlpurifier.org/

HTMLPurifier 白名单机制：使用配置信息来定义 html 标签，标签属性和 CSS 属性数组，在执行 clean() 方法时，只允许配置信息白名单里出现的元素通过，其他都进行过滤

如配置信息：
```$html
'HTML.Allowd' => 'div,me,a[href|title|style],ul,ol,li,p[style],br',
'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family',
```
当用户提交时：
```$html
<a someproperty="somevalue" href="http://example.com" style="color:#ccc;font-size:16px">
    文章内容<script>alert('Alerted')</script>
</a>
```
会被解析为：
```html
<a href="http://example.com" style="font-size:16px">
    文章内容
</a>
```

HTMLPurifier for Laravel 是对 laravel 框架的封装。

安装：`composer require "mews/purifier:~2.0"`

配置：`php artisan vendor:publish --provider="mews\Purifier\PurifierServiceProvider"`

config/purifier.php
```php
<?php

return [
    'encoding'      => 'UTF-8',
    'finalize'      => true,
    'cachePath'     => storage_path('app/purifier'),
    'cacheFileMode' => 0755,
    'settings'      => [
        'user_topic_body' => [
            'HTML.Doctype'             => 'XHTML 1.0 Transitional',
            'HTML.Allowed'             => 'div,b,strong,i,em,a[href|title],ul,ol,ol[start],li,p[style],br,span[style],img[width|height|alt|src],*[style|class],pre,hr,code,h2,h3,h4,h5,h6,blockquote,del,table,thead,tbody,tr,th,td',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,margin,width,height,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],
    ],
];
```

使用：一般在数据入库时候进行过滤
app/Observers/TopicObserver.php
```php
<?php

namespace App\Observers;

use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function saving(Topic $topic)
    {
        $topic->body = clean($topic->body, 'user_topic_body');

        $topic->excerpt = make_excerpt($topic->body);
    }
}
```