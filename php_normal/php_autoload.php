<?php
/**
 * Created by PhpStorm.
 * User: zyw
 * Date: 2017/7/3
 * Time: 23:02
 */
/**
 * PSR-4自动加载器
 * 使用spl_autoload_register()函数注册这个自动加载函数后，遇到下述代码时这个函数
 * 会尝试从/path/to/project/src/Baz/Qux.php文件中加载\Foo\Bar\Qux类：
 *          new \Foo\Bar\Baz\Qux;
 * @param string $class 完全限定的类名
 * @return void
**/
spl_autoload_register(function ($class) {
    // 这个项目的命名空间前缀
    $prefix = 'Foo\\Bar\\';
    // 这个命名空间前缀对应的基目录
    $base_dir = __DIR__ . '/src/';
    //  参数传入的类使用这个命名空间前缀?
    $len = strlen($prefix);
    /**
     * strncmp - 二进制安全比较字符串开头戴的若干个字符
     * int sstrncmp (string $str1, string $str2, int $len)
     * params str1 第一个字符串
     * params str2 第二个字符串
     * params len  最大比较长度
     * return int 如果str1 < str2 返回 < 0; 如果str1大于str2返回 > 0; 相等则返回0;
     */
    if (strncmp($prefix, $class, $len) !== 0) {
        // 不使用，交给注册下一个自动加载器处理
        return;
    }
    // 获取去掉前缀后的类名
    $relative_class = substr($class, $len);
    // 把命令空间前缀替换成基目录
    // 在去掉前缀的类名中， 把命名空间分割符替换成目录分割符，
    // 然后在后面加上.php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    // 如果文件存在，将其导入
    if (file_exists($file)) {
        require $file;
    }
});