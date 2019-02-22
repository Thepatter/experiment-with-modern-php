## 常用设置

### 重置 root 密码
* 停止MySQL服务
* 建立 `init-file` 文件并写入密码赋值语句
```mysql
ALTER USER 'root'@'localhost' IDENTIFIED BY 'password';
```
* 使用特殊 `--init-file` 选项启动
```mysql
mysqld --init-file=/path/to/mysql-init & 
```

### 升级数据库后需要升级数据结构
`mysql_upgrade -u root -p`

### 管理用户

* 查看用户

  ```mysql
  USE mysql;
  SELECT user FROM user;
  ```

* 创建用户账号

  ```mysql
  CREATE USER user_name IDENTIFIED BY 'password'
  ```

* 重命名用户账号

  ```mysql
  RENAME USER old_user_name TO new_user_name
  ```

* 删除用户

  ```mysql
  DROP USER user_name
  ```

* 更改用户密码

  ```mysql
  SET PASSWORD FOR user_name = Password('password')
  ```

* 修改自己密码

  ```mysql
  SET PASSWORD = Password('passwod')
  ```

* 赋权与创建用户

  ```mysql
  grant super on *.* to 'ua'@'%' identified by 'pa';
  ```

  这条语句除了赋权外，还包含

  1.如果用户 `'ua'@'%'` 不存在，就创建这个用户，密码是 pa；

  2.如果用户 ua 已经存在，就将密码修改成 pa

### 管理访问权限

* 查看用户权限

  ```mysql
  SHOW GRANTS FOR user_name
  ```

* 授予用户权限 `GRANT` 语句：要授予的权限，权限范围，用户

  ```mysql
  GRANT SELECT ON database_name.* TO user_name
  ```

* 撤销权限

  ```mysql
  REVOKE SELECT ON database_name.* TO FROM user_name
  ```

  #### GRANT 和 REVOKE 控制权限层次

* 使用 `,` 分割权限可一次授予多种权限 

  ```mysql
  GRANT SELECT, INSERT, DELETE ON database_name.* TO user_name
  ```

* 全局权限，作用于整个 MySQL 实例，这些权限信息保存在 mysql 库的 user 表里。如果要给用户 `ua` 赋予最高权限的话

  ```mysql
  grant all privileges on *.* to 'ua'@'%' with grant option;
  ```

  这个 grant 命令做了两个动作

  1.磁盘上，将 `mysql.user` 表里，用户`'ua'@'%'` 这一行的所有表示权限的字段的值都修改为 Y

  2.内存里，从数组 `acl_users` 中找到这个用户对应的对象，将 `access` 值（权限位）修改为二进制的 “全1”

  在这个 `grant` 命令执行完成后，如果有新的客户端使用用户名字 ua 登录成功，MySQL 会为新连接维护一个线程对象，然后从 `acl_users` 数组里查到这个用户的权限，并将权限值拷贝到这个线程对象中。之后在这个连接中执行的语句，所有关于全局权限的判断，都直接使用线程对象内部保存的权限位

  即：

  1.`grant` 命令对于全局权限，同时更新了磁盘和内存。命令完成后即时生效，接下来新创建的连接会使用新的权限

  2.对于一个已经存在的连接，它的全局权限不受 `grant` 命令的影响

  revoke 命令动作与 grant 类似。

* 库级别权限

  ```java
  // 如果要让用户 ua 拥有库 db1 的所有权限，可以执行下面这条命令
  grant all privileges on db1.* to 'ua'@'%' with grant options;
  ```

  基于库的权限记录保存在 `mysql.db` 表中，在内存里则保存在数组 `acl_dbs` 中，这条 `grant` 命令做了两个动作

  1.在磁盘上，往 mysql.db 表里插入了一行记录，所有权限位字段设置为 “Y”；

  2.内存里，增加一个对象到数组 acl_dbs 中，这个对象的权限位为“全1”

  每次需要判断一个用户对一个数据库读写权限的时候，都需要遍历一次 `acl_dbs` 数组，根据 `user`、`host`、`db` 找到匹配的对象，然后根据对象的权限位来判断。即也同时对磁盘和内存生效

* 表权限和列权限，除了 db 级别的权限外，mysql 支持更细粒度的表权限和列权限。表权限存放在表 `mysql.tables_priv` 中，列权限定义存放在表 `mysql.columns_priv` 中。这两类权限，组合起来存放在内存的 `hash` 结构 `column_priv_hash` 中

  ```mysql
  create table db1.t1(id int, a int);
  
  grant all privileges on db1.t1 to 'ua'@'%' with grant option;
  GRANT SELECT(id), INSERT (id,a) ON mydb.mytbl TO 'ua'@'%' with grant option;
  ```

  这两个权限每次 `grant` 的时候都会修改数据表，也会同步修改内存中的 `hash` 结构。因此，对这两类权限的操作，也会马上影响到已经存在的连接

### grant 与 flush privileges

`flush privileges` 命令会清空 `acl_users` 数组，然后从 `mysql.user` 表中读取数据重新加载，重新构造一个 `all_users` 数组。即，以数据表中的数据为准，会将全局权限内存数组重新加载一遍，对于 db 权限，表权限和列权限，mysql 也做了同样处理。

即，如果内存的权限数据和磁盘数据表相同的话，不需要执行 `flush privileges` 而如果都是用 `grant/revoke` 语句来执行的话，内存和数据表本来就是保持同步更新的。即**正常情况下，grant 命令后，没有必要跟着执行 flush privileges 命令**

#### flush privileges 使用场景

当数据表中权限数据跟内存中的权限数据不一致的时候，`flush privileges` 语句可以用来重建内存数据，达到一致状态

这种不一致往往是由不规则的操作导致的，比如直接用 DML 语句操作系统权限表。