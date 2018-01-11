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

