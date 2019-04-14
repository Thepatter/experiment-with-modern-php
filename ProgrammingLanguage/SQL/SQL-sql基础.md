# Learning SQL

## SQL 基础

__SQL:非过程化语句__

过程化语言对所期望的结果和产生这些结果的执行机制或过程都进行了定义

非过程化语言同样定义了期望结果,但将产生结果的过程留给外部代理来定义

SQL语句只定义必要的输入和输出,而执行语句的方式则交由数据库引擎的一个组件,即优化器(optimizer)处理.

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

ENGINE 设置存储引擎(`Myisam`,` Innodb`), CHARSET 设置编码

##### 修改表

删除,添加或修改表字段及字段名

```mysql
ALTER TABLE table_name DROP field_name;
ALTER TABLE table_name ADD field_name INT AFTER field_name;
ALTER TABLE table_name MODIFY field_name CHAR(10) NOT NULL DEFAULT 'test';
ALTER TABLE table_name CHANGE `要修改的字段名` `新字段名及类型`;
```

修改字段默认值

```sql
ALTER TABLE table_name ALTER field_name SET DEFAULT value;  
```

修改表名

```sql
ALTER TABLE table_name RENAME TO new_table_name;
```

### MYSQL 数据类型

#### 字符型数据

字符型数据可以使用定长或者变长的字符串来实现,其不同点在于固定长度的字符串使用空格向右填充,以保证占用同样的字节数;变长字符串不需要向右填充,并且所有字节数可变,char 列可以设置的最大长度为 255 个字节,而 varchar 列最多可以存储 65535 个字节.(oracle 数据库对 varchar 的使用是个特例,使用 varchar2 类型表示变长字符串列),如果需要存储的数据超过 64kb(varchar 列所能允许上限), 就需要使用文本类型

|    文本类型    | Maximum number of bytes |
| :--------: | :---------------------: |
|  tinytext  |           255           |
|    text    |          65535          |
| mediumtext |        16777215         |
|  longtext  |       4294967295        |

使用文本类型时候:

如果被装载到文本列中的数据超出了该类型的最大长度,数据将会被截断

在向文本列装载数据时,不会消除数据的尾部空格

当使用文本列排序或分组时,只会使用前1024个字节,可修改配置

以上表格只针对MYSQL,SQLServer 对于大的字符型数据只提供 text 类型.而 DB2 和 oracle 使用的数据类型名称为 clob(Character Large Object)

如今MYSQL,允许 varchar 列最大容纳 65535 个字节,这样一般不需要 tinytext 或 text 类型.

Oracle 数据库中, char 列能容纳 2000 个字节,varchar2 能容纳 4000个字节,而 SQLServer 中 char 和 varchar 列都能够容纳 8000 个 字节

#### 数值型数据

MYSQL 整数类型

|    类型     |                  带符号的范围                  |         无符号的范围         |
| :-------: | :--------------------------------------: | :--------------------: |
|  tinyint  |                 -128~127                 |         0~255          |
| smallint  |               -32768~32767               |        0~65535         |
| mediumint |             -8388608~8388607             |       0~16777215       |
|    int    |          -2147483648~2147483647          |      0-4294967295      |
|  bigint   | -9223372036854775808~9223372036853775807 | 0-18446744073709551615 |

MYSQL 浮点类型

|     类型      |                   数值范围                   |      |
| :---------: | :--------------------------------------: | ---- |
| float(p,s)  |    -3.402823466E+38~-1.175494351E-38     |      |
| double(p,s) | -1.7976931348623157~-2.2250738585072014E-308 |      |

浮点列也可以被定义为 unsigned ,但这里只是禁止列中存放负数,并没有改变该列所存储数据的范围

#### 时间数据

MYSQL 的时间类型

|    类型     |        默认格式         |                   允许的值                   |
| :-------: | :-----------------: | :--------------------------------------: |
|   date    |     YYYY-MM-DD      |          1000-01-01~9999-12-31           |
| datetime  | YYYY-MM-DD HH:MI:SS | 1001-01-01 00:00:00 ~ 9999-12-31 23:59:59 |
| timestamp | YYYY-MM-DD HH:MI:SS |     1970-01-01 ~ 2037-12-31 23:59:59     |
|   year    |        YYYY         |                1901-2155                 |
|   time    |      HHH:MI:SS      |           -838:59:59~838:59:59           |

### 操作与修改表

#### 插入数据

insert 语句的 3 个组成部分:

* 所要插入数据的表的名称
* 表中需要使用的列的名称
* 需要插入到列的值
* MYSQL 为时间类型列提供的值为字符串,只要符合上表中列出的格式,MYSQL 就会自动将字符串转换为日期类型

语法

```sql
INSERT INTO TABLE_NAME(field1, field2, field3, filed4) VALUES (value1, value2, 'value3', 'value4');
```

#### 更新数据

语法

```sql
UPDATE table_name SET field1=new-value1, field2=new-value2 WHERE Clause
```

update 可以同时更新一个或多个字段,可以在 where 子句中指定任何条件,可以在一个单独表中同时更新数据.

##### 删除数据

语法

```sql
DELETE FROM table_name WHERE Clause
```

如果没有指定 where 子句, 表中的所有记录将被删除, 可以在 where 子句中指定任何条件,可以在单个表中一次性删除记录.

##### 查询语句

语法

```mysql
SELECT column_name, columen_name FROM table_name WHERE Clause LIMIT N OFFSET M
```

查询语句中可以使用一个或者多个表,表之间使用逗号分割,并使用 WHERE 语句来设定查询条件

SELECT 命令可以读取一条或者多条记录

可以使用星号 (*) 来代替其他字段, SELECT 语句会返回表的所有字段数据

可以使用 LIMIT 属性来设定返回的记录数

可以通过 OFFSET 指定 SELECT 语句开始查询的数据偏移量.默认为0

#### 导致错误的语句及错误

|  错误原因   |                   错误提示                   |
| :-----: | :--------------------------------------: |
|  主键不唯一  | `error 1062(23000):Duplicate entry '1' for key 'PRIMARY'` |
|  不存在外键  | `error 1452(23000): Cannot add or update a child row: a foreign key constraint` |
|  列值不合法  | `error 1265(01000): Data truncated for column "field_name" at row 1` |
| 无效的日期转换 | `error 1292(22007):Incorrect date value: "date_value" for column "field_name"` |

时间转换可以采用显示地指定字符串格式,使用`str_to_date`函数指定所用字符串格式

```mysql
SET birth_date = str_to_date('DEC_21_1980', '%b-%d-%Y');
```

 MYSQL 中将字符串转换为 `datetime` 型值可能用到的格式

|  格式  |     含义     |        示例        |
| :--: | :--------: | :--------------: |
|  %a  |   星期几的简写   |     Sun, Mon     |
|  %b  |   月名称的简写   |     Jan,Feb      |
|  %c  |  月份的数字形式   |       0-12       |
|  %d  |  日在月中的次序   |      00-31       |
|  %f  |    毫秒数     |  000000-999999   |
|  %H  | 24 时格式中小时  |      00-23       |
|  %h  | 12 时格式中的小时 |      01-12       |
| `%i` |   小时中分钟    |      00-59       |
|  %j  |  一年中天的次序   |     001-366      |
|  %M  |   完整的月名称   | January December |
|  %m  |  月份的数字表示   |                  |
|  %p  |  AM 或 PM   |                  |
|  %s  |     秒数     |      00-59       |
|  %W  |   完整的星期名   | Sunday,Saturday  |
|  %w  |  天在星期中的次序  |    0=周日,6=周六     |
|  %Y  |  4位数字的年份   |                  |

