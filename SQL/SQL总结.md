### SQL 总结

### 创建及修改

##### 创建表

创建表需要定义 表名,表字段名,定义每个表字段

结构

```mysql
CREATE TABLE IF NOT EXISTS table_name(
	field_primary_key INT UNSIGNED AUTO_INCREMENT,
    field_name VARCHAR(20) NOT NULL,
  	fiele_introduction VARCHAR(255) DEFAULT 'this is a default message',
    field_gender ENUM('M','F'),
    field_birthday DATE,
	PRIMARY KEY (field_primary_key),
)ENGINE=InnoDB DEFAULT CHARSET=utf8;
```

