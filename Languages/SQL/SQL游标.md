### 游标

#### 游标概览

在数据库中，游标是个重要的概念，提供了一种灵活的操作方法，可以从数据结果集中每次提取一条数据记录进行操作。SQL 中，游标是一种临时的数据库对象，可以指向存储在数据库表中的数据行指针。这里游标充当了指针的左右，可以通过操作游标来对数据行进行操作

#### 使用游标

使用游标，一般需要五个步骤，不同 DBMS 中，使用游标的语法可能略有不同

* 定义游标

  适用于 MySQL、SQL Server、DB2、MariaDB

  ```sql
  DECLARE [cursor_name] CURSOR FOR [select_statement]
  ```

  适用于 Oracle、PostgreSQL

  ```sql
  DECLARE [cursor_name] CURSOR IS [select_statement]
  ```

  要使用 SELECT 语句来获取数据结果集

* 打开游标

  ```sql
  OPEN [cursor_name]
  ```

  使用游标必须先打开游标。打开游标的时候 SELECT 语句的查询结果集就会送到游标工作区

* 从游标中取得数据

  ```sql
  FETCH [cursor_name] INTO [var_name]...
  ```
  
  使用 `cursor_name` 这个游标来读取当前行，并且将数据保存到 `var_name` 这个变量中，游标指针指向下一行。如果游标读取的数据行有多个列名，则在 INTO 关键字后面赋值给多个变量名。
  
  当游标溢出时（当游标指向到最后一行数据后继续执行会报错）。可以定义一个 `continue` 的事件，指定这个事件发生时修改变量 `done` 的值，以此来判断游标是否已经溢出

  ```sql
  DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = true;
  ```


* 关闭游标

  ```sql
  CLOSE [cursor_name]
  ```

  使用完游标后需要关闭该游标。关闭后，就不能再检索查询结果中的数据行，如果需要检索只能再次打开游标

* 释放游标

  ```sql
  DEALLOCATE PREPARE [cursor_name]
  ```

  如果不释放游标，游标会一直存在于内存中，直到进程结束后才会自动释放。


#### 游标应用场景

* 需要找特定数据，用 SQL 查询写起来会比较困难，如两表或多表之间的嵌套循环查找，如果用 JOIN 会非常消耗资源，效率也不高，而用游标则会比较高效

* 游标会带来一些性能问题，在使用游标的过程中，会对数据行进行加锁。而且因为游标是在内存中进行的处理，还会造成内存不足

