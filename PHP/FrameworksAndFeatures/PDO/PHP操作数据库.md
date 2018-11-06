## PHP 操作数据库

__官方文档建议操作 Mysql 数据库时，推荐首选 `mysqli`，其次是 `pdo`__

### MySqli

#### connections 相关

* MySQL 使用 `localhost` 连接数据库时候，底层使用的是 `UNIX socket`, 使用 `IP` 地址连接数据库时候使用的是 `TCP/IP`

* 如果链接为提供参数，默认使用 PHP 设置里的参数。如果主机值未设置或为空，则客户端库将默认使用 `localhost` 的 `unix` 套接字，如果 `socket` 未设置或空，并且请求了 `unix` 套接字连接，则尝试连接到 `/tmp/mysql.sock`上的默认套接字
* 连接池：`mysqli` 扩展支持持久数据库连接，这是一种特殊的连接池。默认情况下，脚本打开的每个数据库连接都由用户在运行时显式关闭，或者在脚本结束时自动释放。持久连接不是，相反，如果打开使用用户名，密码，套接字，端口和默认数据库的同一服务器连接，则将其放入池中以供以后重用。每个 `php` 进程都使用自己的 `mysqli` 连接池。

* PHP 中的配置项目 http://php.net/manual/zh/mysqli.configuration.php


