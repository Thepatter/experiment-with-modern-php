## 调优
###php.ini 文件
以 PHP-FPM 运行 PHP, php.ini 文件在 /etc/php5/fpm/ 目录中.在命令行中执行 php 文件,这个 php.ini 文件在 /etc/php5/cli/ 目录中,如果从
源码安装 PHP, php.ini 文件在配置 PHP 源码文件时指定的 $PREFIX 目录中.
###内存
php.ini 文件中的 memory_limit 文件设置用于设定单个 PHP 进程可以使用的系统内存最大值,默认为128M.
###Zend OPcache
```apacheconfig
opcache.memory_consumption= 64
opcache.interned_strings_buffer = 16
opcache.max_accelerated_files = 4000
opcache.validate_timestamps = 1
opcache.revalidate_frap = 0
opcache.fast_shutdown = 1
```
opcache.memory_consumption = 64 为操作码缓存分配的内存量,分配的内存要能保存应用中所有 PHP 脚本编译得到的操作码.
opcache.interned_strings_buffer = 16 用来存储驻留字符串的内存.
opcache.max_accelerated_files = 4000 操作码中最多能存储多少个 PHP 脚本.这个值一定要比 PHP 应用中的文件数量大
opcache.validate_timestamps = 1  这个值为1时,经过一段时间后,  PHP 会检查 PHP 脚本的内容是否有变化.检查时间的间隔由
opcache.revalidate_freq 设置设定.如果这个值为0, PHP 不会检查 PHP 脚本的内容是否有变化,一般开发1,生产0
opcache.revalidate_frep = 0 设置 PHP 多久检查一次 PHP 脚本内容是否有变化.
opcache.fast_shutdown = 1 让操作码使用更快的停机步骤,把对象析构和内存释放交给 Zend Engine 的内存管理器完成.
###最长执行时间
php.ini 文件中的 max_execution_time 设置单个 PHP 进程在终止之前最长可以运行多少时间,在 PHP 脚本中可以调用 set_time_limit()
函数覆盖
当有需要长时间运行的脚本,则派生一个单独的后台进程,然后返回 HTTP 响应.
`exec('echo "create-report.php" | at now')`
###处理会话
PHP 默认的会话处理程序会把会话数据存储在硬盘中,创建不必要的 I/O, 应将会话保存在内存中, memcached,redis ,若想在 PHP 中访问 Memcached 存储的
数据,要安装 Memcached PECL 扩展,修改 php.ini 配置 `session.save_handler = 'memcached'`, 
`session.save_path = '127.0.0.1:11211'`
###缓冲输出
在较少的片段中把内容传递给访问者的浏览器,能减少 HTTP 请求总数,PHP 默认启用了输出缓冲. PHP 缓冲4096字节的输出之后才会把其中的内容发给 Web 服务器
php.ini 文件中输出缓冲设置 `output_buffering = 4096`, `implicit_flush = false`
###真实路径缓存
PHP 会缓存应用使用的文件路径,这样每次包含或导入文件时候就无需搜索包含路径.真是路径缓存的默认大小为16k, 在一个 PHP 脚本的末尾加上
`print_r(realpath_cache_size())` 输出真是路径缓存的真正大小,把真实路径缓存的大小改为这个真正的值.在 php.ini 文件中设置真实路径缓存的大小,
`realpath_cache_size = 64k`
