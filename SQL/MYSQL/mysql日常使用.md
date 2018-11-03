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

* GRANT 和 REVOKE 控制权限层次

  整个服务器，使用 `GRANT ALL` 和 `REVOKE ALL`

  整个数据库，使用 `ON database.*`

  特定的表，使用 `ON database.table`

  使用 `,` 分割权限可一次授予多种权限 

  ```mysql
  GRANT SELECT, INSERT, DELETE ON database_name.* TO user_name
  ```