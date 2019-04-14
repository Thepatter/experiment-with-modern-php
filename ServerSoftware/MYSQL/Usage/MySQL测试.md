## MySQL 测试

### 单组件式测试工具

#### **mysqlslap**

`mysqlslap` 可以模拟服务器的负载，并输出计时信息。测试时可以执行并发连接数，并指定 SQL 语句（可以在命令行上执行，也可以把 SQL 语句写入到参数中），如果没有指定 SQL 语句，`mysqlslap` 会自动生成查询 `schema` 的 `select` 语句

#### MySQL Benchmark Suite (sql-bench)

可以用于在不同数据库服务器上进行比较测试。它是单线程的，主要用于测试服务器执行查询的速度。结果会显示那种类型的操作在服务器上执行的更快。

这个测试套件包含了大量预定义的测试，容易使用，可以很轻松地用于比较不同存储引擎或者不同配置的性能测试。也可用于高层次测试，比较两个服务器的总体性能。也可以只执行预定义测试的子集。

缺点是，单用户模式，测试的数据集很小且用户无法使用指定的数据。并且同一个测试多次运行的结果可能会相差很大。因为是单线程且串行执行的，所以无法测试多 CPU 的能力，只能用于比较单 CPU 服务器的性能差别。

#### sysbench

多线程系统压测工具。可以根据影响数据库服务器性能的各种因素来评估系统的性能。

#### BENCHMARK 函数

内置 BENCHMARK 函数，可以测试某些特定操作的执行速度。参数可以是需要执行的次数和表达式。表达式可以是任何的标量表达式（返回值是标量的子查询或者函数）可以很方便的测试某些特定操作的性能。执行后的返回值永远是 0，但可以通过客户端返回的时间来判断执行的时间。使用 BENCHMARK 函数来测试性能，表达式必须包含用户定义的变量，否则多次执行同样的表达式会因为系统缓存命中而影响结果

```mysql
SET @input := 'hello world';
SELECT BENCHMARK(100000, MD5(@input));
```

### 测试实例

#### http_load

* 循环请求给定的 URL 列表。

  ```shell
  http_load -parallel 1 -seconds 10 urls.txt
  ```

* 模拟同时5个并发用户进行请求

  ```shell
  http_load -parallel 5 -seconds 10 urls.txt
  ```

* 预估访问请求率（每秒5次）来做压力模拟测试

  ```shell
  http_load -rate 5 -seconds 10 urls.txt
  ```

#### sysbench

* CPU 测试计算素数

  ```shell
  sysbench --test=cpu --cpu-max-prime=20000 run
  ```

* 文件 I/O 基准测试

  测试的第一步是准备阶段，生成测试用到的数据文件，生成的数据文件至少要比内存大。如果文件中的数据能完全放入内存中，则操作系统缓存大部分的数据，导致测试结果无法体现I/O密集型的工作负载。

  ```shell
  sysbench --test= fileio --file- total- size= 150G prepare
  ```

  `seqwr`: 顺序写入

  `seqrewr`: 顺序重写

  `seqrd`: 顺序读取

  `rndrd`: 随机读取

  `rndwr`: 随机写入

  `rdnrw`: 混合随机读/写

  ```shell
  // 运行文件I/O 混合随机读写基准测试
  sysbench --test=fileio --file-total-size=150G --file-test-mode=rndrw/ --init-rng=on--max-time=300--max-requests=0 run
  // 删除生成的测试文件
  sysbench --tesst=fileio --file-total-size=150G cleanup
  ```

* OLTP 基准测试

  模拟简单的事务处理系统的工作负载
