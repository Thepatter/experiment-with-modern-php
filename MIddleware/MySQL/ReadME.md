#### MySQL 笔记规范

##### 名称规范

* MySQL 指数据库
* Server 指数据库服务层
* Client 指数据库客户端
* InnoDB 指 InnoDB 引擎
* MyISAM 指 MyISAM 引擎
* Memory 指 Memory 引擎，memory 指内存

##### 格式规范

* 标题统一使用 h3

* 同功能，同模块配置属性尽量使用表结构，表结构包含：属性、范围、类型、默认值、命令行选项、版本、是否支持动态配置、系统变量

* 所有语句使用 MySQL 代码格式，每一行语句使用英文分号结尾，mysql 关键字使用大写，变量使用 {} 包裹

  ```mysql
  SELECT * FROM WHERE {table}
  ```

* 配置项指可以在 my.cnf 中进行配置的选项，配置项与变量使用行内代码包裹

* 所有命令行工具和图形工具一律使用斜体 *mysqldump*

* 所有函数使用 *field()* 斜体

