## 多角色权限 Laravel-permission

### 安装： `composer require "spatie/laravel-permission:~2.7"`

* 生成数据库迁移文件：`php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"`

  ![35YmbLHqjC](C:\Users\76073\Pictures\35YmbLHqjC.png)

数据表结构

* roles -- 角色的模型表
* permissions -- 权限的模型表
* model_has_roles -- 模型与角色的关联表，用户拥有什么角色在此表中定义，一个用户能拥有多个角色；
* role_has_permissions -- 角色拥有的权限关联表，如管理员拥有查看后台的权限都是在此表定义的，一个角色能拥有多个权限
* model_has_permissions -- 模型与权限关联表，一个模型能拥有多个权限

### 使用

在 user 中使用 laravel-permission 提供的 trait -- HasRoles

```
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
}
```

### 初始化角色和权限

使用数据迁移来实现使出花角色权限相关的代码 `seed_数据库表名称_data`

`php artisan make:migration seed_roles_permissions_data`

*database/migrations/{timestamp}_seed_roles_and_permissions_data.php*

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeedRolesAndPermissionsData extends Migration
{
    public function up()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 先创建权限
        Permission::create(['name' => 'manage_contents']);
        Permission::create(['name' => 'manage_users']);
        Permission::create(['name' => 'edit_settings']);

        // 创建站长角色，并赋予权限
        $founder = Role::create(['name' => 'Founder']);
        $founder->givePermissionTo('manage_contents');
        $founder->givePermissionTo('manage_users');
        $founder->givePermissionTo('edit_settings');

        // 创建管理员角色，并赋予权限
        $maintainer = Role::create(['name' => 'Maintainer']);
        $maintainer->givePermissionTo('manage_contents');
    }

    public function down()
    {
        // 清除缓存
        app()['cache']->forget('spatie.permission.cache');

        // 清空所有数据表数据
        $tableNames = config('permission.table_names');

        Model::unguard();
        DB::table($tableNames['role_has_permissions'])->delete();
        DB::table($tableNames['model_has_roles'])->delete();
        DB::table($tableNames['model_has_permissions'])->delete();
        DB::table($tableNames['roles'])->delete();
        DB::table($tableNames['permissions'])->delete();
        Model::reguard();
    }
}
```

在生成用户填充数据以后，为 1 号和 2 号用户指派角色，修改 run() 方法

*database/seeds/UsersTableSeeder.php*

```php
class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // 初始化用户角色，将 1 号用户指派为『站长』
        $user->assignRole('Founder');

        // 将 2 号用户指派为『管理员』
        $user = User::find(2);
        $user->assignRole('Maintainer');
    }
}
```

### 常用方法

* 新建角色

```php
use Spatie\Permission\Models\Role;
$role = Role::create(['name' => 'Founder']);
```

* 为角色添加权限

```php
use Spatie\Permission\Models\Permission;
Permission::create(['name' => 'manage_contents']);
$role->givePermissionTo('manage_contents');
```

* 赋予用户某个角色

```php
// 单个角色
$user->assignRole('Founder');
// 多个角色
$user->assignRole('writer', 'admin');
// 数组形式的多个角色
$user->assignRole(['writer', 'admin']);
```

* 检查用户角色

```php
// 是否是站长
$user->hasRole('Founder');
// 是否拥有至少一个角色
$user->hasAnyRole(Role::all());
// 是否拥有所有角色
$user->hasAllRole(Role::all());
```

* 检查权限

```php
// 检查用户是否有某个权限
$user->can('manage_contents');
// 检查角色是否拥有某个权限
$role->hasPermissionTo('manage_contents');
```

* 直接给用户权限

```php
// 为用户添加直接权限
$user->givePermissionTo('manage_contents');
// 获取所有直接权限
$user->getDirectPermissions();
```

## 站点权限部署

* 拥有 `manage_contents` 权限的用户允许管理站点内所有话题和回复，包括编辑和删除动作
* Horizon 的控制面板，只有 `站长` 才有权限查看

### 内容管理权限

拥有 `manage_contents` 权限的用户允许管理站点内所有话题和回复：

使用授权策略的策略过滤器机制来实现统一授权的目的，在策略中定义一个 before() 方法，before() 方法会在策略中其他所有方法之前执行，这样提供了全局授权的方案。在 `before()` 方法中存在三种类型的返回值：

* 返回 `true` 是直接通过授权
* 返回 `false` 会拒绝用户所有的授权
* 如果返回的是 null, 则通过其它的策略方法来决定授权通过与否

策略过滤器：对特定用户，你可能希望通过指定的策略授权所有动作。要达到这个目的，可以在策略中定义一个 `before` 方法。`before` 方法会在策略中其它所有方法之前执行。这样提供了一种方式来授权动作而不是指定的策略方法来执行判断，这个功能常见的场景是授权应用的管理员可以访问所有动作

```php
public function before($user, $ability)
{
    if ($user->isSuperAmin()) {
        return true;
    }
}
```

在基类的 before() 方法里做角色权限判断即可用到所有授权类

```php
<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        // 如果用户拥有管理内容的权限的话，即授权通过
        if ($user->can('manage_contents')) {
            return true;
        }
    }
}
```

#### Horizon 控制面板访问权限

Horizon 控制面板页面的路由是 `/horizon` ,默认只能在 `local` 环境中访问。使用 `Horizon::auth` 方法定义具体的访问策略，`auth` 方法接受一个回调函数，此回调函数需要返回 `true` 或 `false` ，从而确认当前用户是否有权限访问 `Horizon` 仪表盘

*app/Providers/AuthServiceProvider.php*

```php
<?php
class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        \Horizon::auth(function ($request) {
            // 是否是站长
            return \Auth::user()->hasRole('Founder');
        });
    }
}
```

