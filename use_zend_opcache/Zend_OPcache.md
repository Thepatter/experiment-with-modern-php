###Zend OPcache - PHP内置字节码缓存功能。
####PHP是解释型语言，PHP解释器执行PHP脚本时会解析PHP脚本代码，把PHP代码编译成一系列Zend操作码，
####然后执行字节码。每次请求PHP文件都是这样，会消耗很多资源，如果每次HTTP请求PHP都必须不断解析、
####编译和执行PHP脚本、消耗的资源更多。字节码缓存能存储预先编译好的PHP字节码。这意味着，请求PHP脚本时，
####PHP解释器不用每次都读取、解析和编译PHP代码。PHP解释器会从内存中读取预先编译好的字节码，然后立即执行。
####这样能节省很多时间，极大地提升应用的性能
###启用Zend OPcache
####默认情况下，Zend OPcache没有启用，编译PHP时我们必须明确指定启用Zend OPcache
####编译PHP，执行`./configure`命令时必须包含以下选项`--enable-opcache`。编译好PHP后，还必须在php.ini文
####件中指定Zend OPcache扩展的路径。`zend_extension=/path/to/opcache.so`可以使用`php-config --extension-dir`
####命令找到这个PHP扩展所在目录
###配置Zend OPcache
####启用Zend OPcache后，在php.ini文件中配置Zend OPcache的设置。
####Zend OPcache推荐配置
####`zend_extension=opcache.so`
####`opcache.enable_cli=1`
####`opcache.memory_consumption=128 //共享内存大小，可调` 
####`opcache.interned_strings_buffer=8 //interned string的内存大小，可调` 
####`opcache.max_accelerated_files=4000 //最大缓存的文件数目`
####`opcache.save_comments=0    //不保存文件／函数的注释`
####`opcache.validate_timestamps = 1 //在生产环境中设为"0"`
####`opcache.revalidate_frep = 0 //检查文件更新时间间隔`
####`opcache.memory_consumption = 64`
####`opcache.interned_strings_buffer = 16`
####`opcache.max_accelerated_files = 4000`
####`opcache.fast_shutdown = 1`
###使用Zend OPcache
####启用后会自动运行。Zend OPcache会自动在内存中缓存预先编译好的PHP字节码，如果缓存了某个文件的字节码，就
####执行对应的字节码。如果`php.ini`文件中的`opcache.validate_timestamps`指令为0，Zend OPcache不会察
####觉到PHP脚本的变化。如下修改php.ini文件配置来启用自动重新验证缓存功能
####`opcache,validate_timestamps=1` `opcache.revalidate_freq=0`