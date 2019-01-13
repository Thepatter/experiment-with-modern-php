## MySQL 中和 binlog 有关的参数

### binlog 配置参数相关

* `log_bin`

  表示启动 `binlog` 的功能，并指定路径

* `log_bin_index`

  设置此参数是指定所有文件的路径与名称

* `binlog_do_db`

  只记录指定数据库的日志

* `binlog_ignore_db`

  不记录指定的数据库的二进制日志

* `max_binlog_cache_size`

  `binlog` 使用的内存最大的尺寸

* `bingo_cache_size`

  `binlog` 使用的内存大小，可以通过状态变量 `binlog_cache_use` 和 `bingo_cache_disk_use` 来调试

* `binlog_cache_use`

  使用日志缓存的事务数量

* `binlog_cache_disk_use`

  使用二禁止日志缓存但超过 `binlog_cache_size` 值并使用临时文件来保存事务中的语句的事务数量

* `max_binlog_size`

  `binlog` 最大值，最大和默认值是 1 GB，该设置并不能严格控制 `binlog` 的大小。

* `sync_binlog`

  同步写入磁盘，为 0 时仅仅将数据写入 `binlog` 文件，但不执行 `fsync` ，为正整数时候，即提交几次失误后进行 `fsync`

###mysql 中操作 binlog 相关

*  `show binary logs`

  查看 `binlog` 日志

* `show variables like 'expire_logs_days`

  显示自动删除过期日志的天数，默认为 0 不删除

* `set global expire_logs_days = 3`

  日志保留三天，3 天后自动过期

* `reset master`

  删除 `master` 的 `binlog`，即手动删除所有日志

* `reset slave`

  删除 `slave` 的中继日志

* `purge master logs before '2012-03-30 17:20:00'`

  删除指定日期以前的日志索引中的 `binlog` 日志

* `purge master logs to 'binlog.000002'`

  删除指定日志文件的日志索引中 `binlog` 日志文件

* `set sql_log_bin=1/0`

  开启或关闭日志文件

* `show master logs`

  查看 `master` 的 `binlog` 日志列表

* `show binary logs`

  查看 `master` 的 `binlog` 日志文件大小

* `show master status`

  `master` 日志文件的状态信息

* `show slave hosts`

  显示当前 `slave` 的列表

* `flush logs`

  产生新的 `binlog` 文件

