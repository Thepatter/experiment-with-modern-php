##错误和异常
###异常
异常是Exception类对象,在遇到无法修复的状况时抛出.Exception对象与其他任何PHP对象一样,使用new关键字实例化,Exception对象由两个主要的属性:一个是
消息,一个是可选的数字代码.消息用于描述出现的问题;数字代码是可选的,用于为指定的异常提供岁上下午.实例化Exception对象时,可以像下马这样设定消息和可选
的是数字代码 `$exception = new Exception('Danger, Will Robinsson!', 100)` 可以使用公开实例方法 getCode() 和 getMMessage() 获取.
####抛出异常
抛出异常后程序会立即停止执行,后续的PHP代码都不会执行.抛出异常的方式是使用throw关键字,后面跟要抛出的Exception实例.
`throw new Exception('Something went wrong. Time for lunch!)`
####捕获异常
拦截并处理潜在异常的方式是,把可能抛出异常的代码放在 try/catch 块中.可以使用多个 catch 来捕获多个异常
####异常处理程序
PHP 允许我们们注册一个全局异常处理程序,捕获所有未捕获的异常.如果没有成功捕获并处理异常,通过这个措施实施可以给 PHP 应用的用户显示和上司的错误消息.
异常处理程序是任何可调用的代码,异常处理程序必须接收一个类型为 Exception 的参数,异常处理函数使用set_exception_handle() 函数注册.
`set_exception_handle(function $e) { // 处理并记录异常 });`
使用自定义异常处理程序后,如果要还原之前异常处理程序则调用 `restore_exception_handle()`
###错误
PHP 错误处理原则:一定要让 PHP 报告错误,在开发环境要显示错误,在生产环境中不能显示错误,在开发环境和生产环境中都要记录错误.
在 php.ini 文件中设置开发环境的错误报告如下.
显示错误 `display_startup_errors = On`, `display_errors = On`
报告所有错误 `error_reporting= -1`
记录错误 `log_errors= On`
在 生产环境中 php.ini 文件的设置错误报告如下,
不显示错误 `display_startup_errors = Off`, `display_errors = On`
报告所有错误 `error_reporting = -1`
记录错误  `log_errors = On`
####错误处理程序
错误处理程序可以是任何可调用的代码,我们要在错误处理程序中调用 die() 或 exit() 函数,如果在错误处理程序中不手动终止执行 PHP 脚本, PHP 脚本会从出
错的地方继续执行.注册全局错误处理程序的方式使用 `set_error_handle()` 函数
` set_error_handler(function ($errno, $errstr, $errfile, $errline) { // 处理错误 })`
可调用的错误处理程序接收五个参数
$errno  错误等级 (对应于一个 E_* 常量)
$errstr 错误消息
$errfile    发生错误的文件名
$errline    发送错误的行号
$errcontext 一个数组,执行错误发生时候可用的符号表.
PHP 错误转换只能转换满足 php.ini 文件中的error_reporting 指令设置的错误。
####在开发环境中处理错误和异常
可以使用 Whoops 组件来处理错误。包名为 filp/whoops
####在生产环境中处理错误和异常
PHP error_log() 函数可以把错误消息写入文件系统或syslog，还可以通过电子邮件发送错误消息。