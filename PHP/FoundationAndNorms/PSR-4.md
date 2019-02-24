## PSR-4 自动加载器策略

### PSR-4 策略概述

PSR-4 描述的策略用于在运行时查找并加载 PHP 类，接口和性状。PSR-4 规范不要去改变代码的实现方式，只建议如何使用文件系统目录结构和 PHP 命名空间组织代码。PSR-4 自动加载器策略依赖 PHP 命名空间和文件系统目录结构查找并加载 PHP 类，接口和性状

PSR-4 的精髓是把命名空间的前缀和文件系统中的目录对应起来。

#### 编写 PSR-4 自动加载器

符合 PSR-4 规范的代码有个命名空间前缀对应与文件系统中的基目录，这个命名空间前缀中的自命名空间对应与这个基目录里的子目录。

*实现自动加载器，这个自动加载器会根据PSR-4自动加载器策略查找并加载类，接口和性状（http://bit.ly/php-fig）*

```php
/**
 * @param string $class 完全限定的类名
 * $return void
 */
spl_autoload_register(function ($class) {
    // 项目的命名空间前缀
    $prefix = 'Foo\\Bar\\';
    // 这个命名空间前缀对应的基目录
    $base_dir = __DIR__ . '/src/';
    // 参数传入的类使用这个命名空间前缀吗？
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // 不使用,交给注册的下一个自动加载器处理
        return ;
    }
    // 获取去掉前缀后的类名
    $relative_class = substr($class, $len);
    // 把命名空间前缀替换成基目录,在去掉前缀的类名中，把命名空间分隔符替换成目录分隔符，然后加 .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    // 如果文件存在，将其导入
    if (file_exists($file)) {
        require $file;
    }
});  
```

