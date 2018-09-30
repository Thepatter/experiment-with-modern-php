## PHP 7 新特性

**类型提示**

PHP 7 之前的函数参数类型提示，只支持对象(类，接口),和数组类型。PHP 7 开始支持标量参数类型提示

`int float string bool` PHP 7 函数方法新增返回值类型提示,参数类型提示为接口的时候，实参可以为接口的实现类，及接口实现类的子类。参数类型提示为类时，实参可以为类的实例，及子类的实例。但不能是父类的实例。

**严格类型约束**

`declare(strict_types=1)`

这是文件级别的指令，不影响其他包含文件。

###生成器特性

**生成器委托**yield\<expr>,\<expr>的结果是可遍历的对象或数组

PHP  迭代生成器，每当产生一个数组元素则用 yield 关键词返回，并且执行函数暂停，当执行函数 next 方法时，则会从上一次被 yield 的位置开始继续执行。

```php
<?php
declare(strict_types=1);
$seh_seh_liam = function () {
  $generator = function () {
    yield from range(1, 3);
    foreach (range(4, 6) as $i) {
      yield;
    }
  };
  foreach ($generator() as $value) {
    echo 'hello' , PHP_EOL;
  }
};
$seh_seh_liam();
```

**生成器返回表达式**

```php
<?php
$traverser = (function () {
   yield 'foo';
   yield 'bar';
   return 'value';
})();
$traverser->getReturn();
foreach ($traverser as $value) {
   echo "{$value}", PHP_EOL;
}
$traverser->getReturn();
```

**生成器与 Coroutine **

```php
declare(strict_types=1);
class Coroutine
{
    public static function create(callable  $callback): Generator
    {
        return (function () use ($callback) {
            try {
                yield $callback;
            } catch (Exception $e) {
                echo "OH.. an error, but don't care and continue", PHP_EOL;
            }
        })();
    }
    public static function run(array $cos)
    {
        $cnt = count($cos);
        while ($cnt > 0) {
            $loc = random_int(0, $cnt-1);
            $cos[$loc]->current()();
            array_splice($cos, $loc, 1);
            $cnt--;
        }
    }
}
$co = new Coroutine();
$cos = [];
for ($i = 1; $i <= 10; $i++) {
    $cos[] = $co::create(function () use ($i) {
        echo "Co.{$i}.", PHP_EOL;
    });
}
$co::run($cos);
$cos = [];
for ($i = 1; $i <= 20; $i++) {
    $cos[] = $co::create(function () use ($i) {
        echo "Co.{$i}.", PHP_EOL;
    });
}
$co::run($cos);
```

**空合并操作符**

`$name =  $name ?? "NoName"; // 如果 $name 有值就取其值，否则设 $name 成 “NoName”`

**飞船操作符**

形式 `(expr) <=> (expr)`

左边运算对象小，则返回 -1；左、右两边运算对象相等，则返回 0；左边运算对象大，则返回1

```php
$name = ["Simen"m "Suzy", "Cook", "Stella"];
usort($name, function ($left, $right) {
  	return $left <=> $right;
});
print_r($name);
```

**常量数组**

PHP 7 之前只允许类、接口中使用常量数组，PHP 7 支持非类、接口的普通常量数组。 

可以通过 define() 定义常量数组

```
<?php
define('ANIMALS', [
  	'dog',
  	'cat',
  	'bird'
]);
```

**匿名类**

```
<?php
interface Logger {
  	public function log(string $msg);
}
class Application {
  	private $logger;
  	public function getLogger(): Logger
  	{
        return $this->logger;
  	}
  	public function setLogger(Logger $logger) {
        $this->logger = $logger;
  	}
}
$app = new Application;
$app->setLogger(new class implements Logger {
    public function log(string $msg) {
       echo $msg;
    }
});
var_dump($app->getLogger());
```



**变量语法**，由左向右结合

```
$goo = [
  	'bar' => [
      	'baz' => 100,
      	'cug' => 900,
  	]
];
$foo = "goo";
$$foo["bar"]["baz"] // php 7 解析为：($$foo)['bar']['baz']; php 5 为：${$foo['bar']['baz']};
```

**可见性修饰符的变化**

PHP 7.1 之前的类常量是不允许添加可见性修饰符的，类常量可见性相当于 public。PHP7.1 为类常量添加了可见性修饰符支持特性。

```
函数/方法：public, private,protected,abstract,final
类：abstract, final
属性/变量：public, private, protected
类常量：public,private,protected
```

**可空类型**

类型现在允许为空，当启用这个特性时，传入的参数或者函数返回的结果要么是给定的类型，要么是 null,可以通过在类型前面加上一个问号来使之成为可为空的。

```
<?php
function test(? string $name)
{
  	var_dump($name);
}
test('tpunt'); 		// string(5) "tpunt"
test(null);			// NULL
test();		// Uncaught Error: Too few arguments to function test(), 0 passed in...
```

**Void 函数**

返回值类型 void。返回值声明为 void 类型的方法要么干脆省去 return 语句，要么使用一个空的 return 语句。对于 void 函数来说。null 不是一个合法的返回值。

```
<?php
function swap(&$left, &$right):void
{
  	if ($left === $right) {
      	return;
  	}
  	$tmp = $left;
  	$left = $right;
  	$right = $tmp;
}
$a = 1;
$b = 2;
var_dump(swap($a, $b), $a, $b);
```

####Closure::call()

暂时绑定一个方法到对象上闭包并调用它

```
<?php
class A
{
  	private $x = 1;
}

```

