### mysqld tools

#### mysqladmin

* status

  显示服务器状态信息，包括正常运行时间、线程数、查询数、常规统计数据，提供服务器健康状况的一个快照

* extended-status

  显示系统统计信息的完整列表，类似 `SQL SHOW STATUS`

* processlist

  显示当前进程的列表，类似 `SQL SHOW PROCESSLIST`

* Kill thread id

  杀死某个指定的线程。它与 processlist 结合使用，可以帮助管理失控或挂起的线程

* variables

  显示服务器变量和值，类似 `SQL SHOW VARIABLES`

```shell
# 每 3 秒刷新一次进程列表
mysqladmin -p processlist --sleep 3
```

#### mysqlbinlog

* short-form

  该选项只输出发出的SQL语句信息，而忽略二进制日志中事件的注释信息。当仅仅使用mysqlbinlog将事件重放到服务器时，这个选项是非常有用的。如果想审查binlog文件中的错误，就需要注释信息，不能使用该选项

* force-if-open

  如果binlog没有被正确关闭，比如binlog文件仍在写入或服务器崩溃，mysqlbinlog都将输出一条警告说这个binlog文件没有被正确关闭。该选项用于禁止输出那条警告。

* Base64-outout=never

  该选项阻止mysqlbinlog输出base64编码的事件。如果要输出base64编码的事件，也会输出二进制日志的Format_description事件，表明使用了哪种编码。这对基于语句的复制是不必要的，因此使用这个选项来阻止该事件。

* `read-from-remote-server`

  选项读取远程 binlog 文件，而不需要给出全路径。

  ```shell
  # 获取远程 binlog 文件备份
  mysqlbinlog --raw --read-from-remote-server \
  	--host=master.example.com --user=repl_user \
  	master-bin.000012 master-bin.000012
  ```

* `result-file=prefix`

  该选项给出创建写入文件的前缀，这个前缀可以是目录名（带有反斜杠），或者任何其他前缀。默认值是一个空字符串，所以如果不使用这个选项，即将写入的文件与master上的文件同名。

* `to-last-log`

  一般来说，只有命令行给定的文件才会被传送。但如果提供了这个选项，只需要给定开始传送的二进制日志文件，然后 mysqlbinlog 会把后面所有文件都传送出去。

* `stop-never`

  到达最后一个日志文件末尾也不停止，继续等待更多输入。在做即时恢复备份的时候，这个选项很有用。