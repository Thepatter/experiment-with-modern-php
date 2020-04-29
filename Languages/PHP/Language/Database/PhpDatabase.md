### PHP 操作数据库

#### PDO

https://www.php.net/manual/zh/book.pdo.php

* `top` 和 `limit` 不支持预处理语句，必须拼接
* `fetchAll(PDO::FETCH_ASSOC)`  获取关联数组结果集

#### MySQL

##### Mysqli

https://www.php.net/manual/zh/book.mysqli.php

* MySQL 使用 `localhost` 连接数据库时候，底层使用的是 `UNIX socket`, 使用 `IP` 地址连接数据库时候使用的是 `TCP/IP`
* 如果链接为提供参数，默认使用 PHP 设置里的参数。如果主机值未设置或为空，则客户端库将默认使用 `localhost` 的 `unix` 套接字，如果 `socket` 未设置或空，并且请求了 `unix` 套接字连接，则尝试连接到 `/tmp/mysql.sock`上的默认套接字
* 连接池：`mysqli` 扩展支持持久数据库连接，这是一种特殊的连接池。默认情况下，脚本打开的每个数据库连接都由用户在运行时显式关闭，或者在脚本结束时自动释放。持久连接不是，相反，如果打开使用用户名，密码，套接字，端口和默认数据库的同一服务器连接，则将其放入池中以供以后重用。每个 `php` 进程都使用自己的 `mysqli` 连接池。
* PHP 中的配置项目 http://php.net/manual/zh/mysqli.configuration.php
* `mysqli` 使用预处理语句防止`sql`注入, `mysqli::query()`, 需要自行处理 sql 注入，转义参数。

MySQL 数据类型是二进制时，预处理语句绑定参数必须使用 null 占位，并使用 send_long_data 插入对应位置的二进制数据

```php
$stmt = $mysqli->prepare("INSERT INTO messages (message) VALUES (?)");
$null = NULL;
$stmt->bind_param("b", $null);
$fp = fopen("messages.txt", "r");
while (!feof($fp)) {
    // 第一个参数为二进制位置索引，第二个参数为值
    $stmt->send_long_data(0, fread($fp, 8192));
}
fclose($fp);
$stmt->execute();
```



