## Laravel 框架常用功能

### 模型关联

#### 模型关联,一对一关联.

一个 `user` 模型可能关联一个 `phone` 模型.为了定义这个关联,要在 `User` 模型中写一个 phone 方法,在 phone 方法内部调用 `hasOne` 方法返回其结果:

```php
class User extends Model
{
  	// 获得与用户关联的电话记录
  	public function phone()
  	{
      	return $this-hasOne('App\Phone');
  	}
}
```

`hasOne` 方法的第一个参数是关联模型的类名,关联关系定义好后,就可以使用 Eloquent 动态属性获得相关记录

`$phone = User::find(1)->phone`

Eloquent 会基于模型名决定外键名称.在当前场景中,Eloquent 假设 `phone` 模型有一个 `user_id` 外键,如果外键名不是这个,可以给 `hasOne` 方法传递第二个参数覆盖默认使用的外键名:

`return $this->hasOne('App\phone', 'foreign_key')`

Eloquent 假定外键值与父级 `id` 或自定义 `$primaryKey` 列的值相匹配.Eloquent 将在 phone 记录 user_id 列中查找与用户表 `id` 列相匹配的值.如果希望该关联使用`id`以外的自定义键,则给 `hasOne` 方法传递第三个参数:

`return $this-hasOne('App\Phone', 'foreign_key', 'local_key')`

#### 反向关联

已经从 `User` 模型访问到 `phone` 模型,现在再在 `phone` 模型中定义一个关联,此关联能让我们访问到拥有此电话的`user` 模型,使用 `belongsTo` 方法

```php
class Phone extends Model
{
  	// 获得拥有此电话的用户
  	public function user()
  	{
      	return $this->belongsTo('App\User');
  	}
}
```

Eloquent 会尝试匹配 `Phone` 模型上的 `user_id` 至 `User` 模型上的 `id`,通过检查关系方法的名称并使用 `_id` 作为后缀名来确定默认外键名称,如果 `phone` 模型的外键不是 `user_id`,那么将自定义键名作为第二个参数传递给 `belongsTo`方法:

`return $this->belongsTo('App\User', 'foreign_key')`

如果父级模型没有使用 `id` 作为主键,或者是希望用不同的字段来连接子级模型,则向 `belongsTo` 方法传递第三个参数指定父级数据表的自定义键:

`return $this->belongsTo('App\User', 'foreign_key', 'other_key)`

### 预加载

ORM 关联数据读取中存在 N+1 情况(关联数据遍历查询时),使用预加载功能规避.

当通过属性访问 Eloquent 关联时,该关联数据会被延迟加载,意味着该关联数据只有在你使用属性访问它时才会被加载,不过,Eloquent 可以在查找上层模型时预加载关联数据来规避 N + 1 查找的问题

使用 `with` 方法来指定想要预加载的关联数据:

`$topic = Topic::with('user', 'category')->paginate(30)`

`with` 方法提前加载了后面需要用到的关联属性 `user` 和 `category` ,并做了缓存,后面即使是在遍历数据时使用这两个关联属性,数据已经被预加载并缓存,因此不会再产生多余的 SQL 查询

### 本地作用域

本地作用域能定义通用的约束集合以便再应用中复用.定义一个作用域,只需要在 `scope` 前加上一个 Eloquent 模型方法即可

```php
public function scopeActive($query)
{
  	return $query->where('active', 1);
}
public function scopePopular($query)
{
  	return $query->where('votes', '>', 100);
}
```

定义范围之后,可以在查询模型时候调用 `scope` 方法,在调用方法时,不应该包含 `scope` 前缀,甚至可以链式调用不同的 `scope` 

`$user = App\User::popular()->active()->orderBy('created_at')->get()`

动态作用域,只需将附加参数添加到作用域,作用域参数应该在 `$query` 参数后定义:

```php
public function scopeOfType($query, $type)
{
  	return $query->where('type', $type);
}
```

### 模型观察器

如果要给某个模型监听很多事情,则可以使用观察器将所有监听器分组到一个类中,观察器类里的方法名应该对应 Eloquent 中你想监听的事件,每种方法接收 model 作为其唯一的参数, laravel 没有为观察器设置默认的目录,可以创建任何喜欢目录来存放

```php
class UserObserver
{
    /**
     * 监听用户创建的事件。
     *
     * @param  User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * 监听用户删除事件。
     *
     * @param  User  $user
     * @return void
     */
    public function deleting(User $user)
    {
        //
    }
}
```

要注册一个观察器,需要在模型上使用 observe 方法,可以在服务提供器中的 boot 方法注册观察器.

```php
<?php

namespace App\Providers;

use App\User;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * 运行所有应用.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
    }

    /**
     * 注册服务提供.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
```

### 路由模型绑定

当向路由或控制器行为注入模型 id 时,就需要查询这个 ID 对应的模型, Laravel 为路由模型绑定提供了一个直接自动将模型实例注入到路由中的方法.

#### 隐式绑定

laravel 会自动解析定义在路由或控制器行为中与类型提示的变量名匹配的路由段名称的 Eloquent 模型

```php
Route::get('api/users/{user}', function (App\User $user) {
  	return $user->email;
});
```

在上面例子中,由于 `$user` 变量被类型提示为 Eloquent 模型 `App\User` ,变量名称又与 URI 中 `{user}` 匹配,因此,laravel 会自动注入与请求 URI 中传入的 ID 匹配的用户模型实例,如果在数据库中找不到对应的模型实例,将会自动生成 404 异常.

#### 自定义键名

如果想要模型绑定在检索给定的模型类时使用除 `id` 之外的数据库字段,可以在 Eloquent 模型上重写 `getRouteKeyName`

```php
// 为路由模型获取键名
public function getRouteKeyName()
{
  	return 'slug';
}
```

#### 显式绑定

要注册显式绑定,使用路由器的 model 方法来为给定参数指定类,在 `RouteServiceProvider` 类中 `boot` 方法内定义这些显式模型绑定:

```php
public function boot()
{
  	parent::boot();
  	Route::model('user', App\User::class);
}
```

接着,定义一个包含 `{user}`  参数的路由

```php
Route::get('profile/{user}', function (App\User $user) {
  	
});
```

#### 自定义解析逻辑

如果要使用自定义的解析逻辑.就使用 `Route::bind` 方法,传递到 `bind` 方法的闭包会接受 URI 中大括号对应的值,并且返回想要在该路由中注入的类的实例:

```php
public function boot()
{
  	parent::boot();
  	Route::bind('user', function ($value) {
      	return App\User::where('name', $value)->first();
  	});
}
```

### Laravel 服务容器解析

laravel 服务容器是用于管理类的依赖和执行依赖注入的工具,依赖注入是指:__类的依赖项通过构造函数,或者某些情况下通过 setter 方法 注入到类中__

```php
<?php

namespace App\Http\Controllers;

use App\User;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * 用户存储库的实现。
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * 创建新的控制器实例。
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * 显示指定用户的 profile。
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = $this->users->find($id);

        return view('user.profile', ['user' => $user]);
    }
}
```

这个例子中,控制器 `UserController` 需要从数据源中获取 `users` ,因此,要注入 `users` 的服务.在这种情况下.`UserRepository` 可能是使用 `Eloquent` 从数据库中获取 `user` 信息.因为 Repository 是通过 `UserRepository` 注入的.

### laravel 使用 redis 队列

1.安装 `composer require "predis/predis:~1.0"`

2.创建队列任务失败任务表 `php artisan queue:failed-table` , `php artisan migrate`

3.生成任务类 `php artisan make:job TranslateSlug`,在目录 `app/Jobs` 下生成新任务

4. `app/Jobs/TranslateSlug.php`

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

该类实现了 `Illuminate\Contracts\Queue\ShouldQueue` 接口,该接口表明 `laravel` 应该将该任务添加到后台的任务队列中,而不是同步执行.

引入 `SerializesModels` trait , Eloquent 模型会被优雅的序列化和反序列化,队列任务构造器中接收了 Eloquent 模型,将会只序列化模型的 ID. 这样子在任务执行时,队列系统会从数据库中自动的根据 ID 检索出模型实例.这样可以避免序列化完整的模型可能在队列中出现的问题.

`handle` 方法会在队列任务执行时被调用.可以在任务的 `handle` 方法中可以使用类型提示进行依赖的注入,`Laravel` 的服务容器会自动的将这些依赖注入进去,与控制器方法类似.

还有一点需要注意，我们将会在模型监控器中分发任务，任务中要避免使用 Eloquent 模型接口调用，如：`create()`, `update()`, `save()` 等操作。否则会陷入调用死循环 —— 模型监控器分发任务，任务触发模型监控器，模型监控器再次分发任务，任务再次触发模型监控器.... 死循环。在这种情况下，使用 `DB` 类直接对数据库进行操作即可

5.任务分发

修改 Topic 模型监控器,将 Slug 翻译的调用修改为队列执行的方式

`*app/Jobs/TranslateSlug.php`

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

6.在命令行启动队列系统，队列在启动完成后会进入监听状态：`php artisan queue:listen`

7.在开发环境中，我们为了测试方便，直接在命令行里调用 `artisan horizon` 进行队列监控。然而在生产环境中，我们需要配置一个进程管理工具来监控 `artisan horizon` 命令的执行，以便在其意外退出时自动重启。当服务器部署新代码时，需要终止当前 Horizon 主进程，然后通过进程管理工具来重启，从而使用最新的代码。

简而言之，生产环境下使用队列需要注意以下两个问题：

1. 使用 Supervisor 进程工具进行管理，配置和使用请参照 [文档](https://d.laravel-china.org/docs/5.5/horizon#Supervisor-%E9%85%8D%E7%BD%AE) 进行配置；
2. 每一次部署代码时，需 `artisan horizon:terminate` 然后再 `artisan horizon` 重新加载代码。