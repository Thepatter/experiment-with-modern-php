### 触发器

**触发器是 MySql 响应 DELETE，INSERT，UPDATE，而自动执行的一条 MySQL 语句（或位于 BEGIN 和 END 语句之间的一组语句）**

#### 创建触发器

* 唯一的触发器名
* 触发器关联的表
* 触发器应该响应的活动（DELETE，INSERT,或UPDATE）
* 触发器合适执行（处理之前或之后）
* 保持数据库的触发器名称唯一
* 仅表支持触发器，视图和临时表都不支持，触发器按每个表每个事件每次地定义，每个表每个事件每次只允许一个触发器，因此，每个表最多支持 6 个触发器（每条 INSERT，UPDATE，DELTETE 的之前和之后）单一触发器不能与多个事件或多个表关联，如果需要一个对 INSERT 和 UPDATE 操作执行的触发器，则应该定义两个触发器
* 如果 `before` 触发器失败，则 Mysql 将不执行请求的操作，此外，如果 `before` 触发器或语句本身失败，MySQL 将不执行 `after` 触发器（如果有的话）
* 触发器不能修改，只能删除 `DROP TRIGGER trigger_name`

```mysql
CREATE TRIGGER newproduct AFTER INSERT ON products FOR EACH ROW SELECT 'product added'
```

**`CREATE TRIGGER` 用来创建名为 newproduct 的新触发器。触发器可在一个操作发生之前或之后执行，这里给出了 `AFTER INSERT` 所在触发器将在 `INSERT` 语句成功执行后执行。这个触发器还指定 `FOR EACH ROW`， 因此代码对每个插入行执行**。

#### INSERT 触发器

* 在 `INSERT` 触发器代码内，可引用一个名为 `NEW` 的虚拟表，访问被插入的行
* 在 `BEFORE INSERT` 触发器中，`NEW` 中的值也可以被更新（允许更改被插入的值）
* 对于 `AUTO_INCREMENT` 列，`NEW` 在 `INSERT` 执行之前包含 0，在 `INSERT` 执行之后包含新的自动生成的值
* 通常 `BEFORE` 用于数据验证和数据格式化

 ```mysql
CREATE TRIGGER neworder AFTER INSERT ON orders FOR EACH ROW SELECT NEW.order_num
 ```

#### DELETE 触发器

* 在 `DELETE` 触发器代码内，可以引用一个名为 `OLD` 的虚拟表，访问被删除的行
* `OLD` 中值全部都是只读的，不能更新

```mysql
CREATE TRIGGER deleteorder DEFORE DELETE ON orders FOR EACH ROW
BEGIN
	INSERT INTO archive_orders(order_num, order_data, cust_id)
	VALUEs(OLD.order_num, OLD.order_date, OLD.cust_id);
END;
```

#### UPDATE 触发器

* 在 `UPDATE` 触发器中，可以引用一个名为 `OLD` 的虚拟表访问以前(`UPDATE` 语句前)的值，引用一个名为 `NEW`的虚拟表访问新跟新的值
* 在 `BEFORE UPDATE` 触发器中，`NEW` 中的值可能也被更新（允许更改将要用于 `UPDATE` 语句中的值）
* `OLD` 中的值全都是只读的，不能更新

```MYSQL
CREATE TRIGGER updatevendor BEFORE UPDATE ON verdors FOR EACH ROW SET NEW.vend_state = Upper(NEW.vend_state);
```

### 事务处理

* 事务：`transaction` 一组 SQL 语句
* 回退：`rollback` 撤销指定 SQL 语句的过程
* 提交：`commit` 指将未存储的 SQL 语句结果写入数据库表
* 保留点：`savepoint` 指事务处理中设置的临时占位符（place-holder),你可用对它发布回退（并非回退整个事务）

* 事务处理的关键在于将 SQL 语句组分解为逻辑块，并明确规定数据何时该回退，何时不应该回退

```mysql
START transaction;
DELETE FROM ordertotals;
SLECT * FROM ordertotals;
ROLLBACK;
SELECT * FROM ordertotals;
```

```mysql
START TRANSACTION;
DELETE FROM orderitems WHERE order_num = 20010;
DELETE FROM orders WHERE order_num = 20010;
COMMIT;
```

* 为了支持回退部分事务，必须能在事务处理块中合适的位置放占位符。这样，如果需要回退，可以回退到某个占位符

* 每个占位符名称唯一 `SAVEPOINT savepoint_name`
* 回退到保留点 `ROLLBACK TO savepoint_name`

* 保留点越多，事务控制越细致，执行完事务后会自动释放保留点