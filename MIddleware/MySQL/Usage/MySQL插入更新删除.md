### 插入,更新,删除数据

#### 插入数据

* 插入完整的行 `insert into table_name(table_field) values(table_field_value)`
* 如果数据检索是最重要的,则可以通过在`insert` 和 `into` 之间添加关键字 `low_priority` 指示MySQL降低 `insert`语句的优先级 `insert low_priority into` 也适用于 `update` 和 `delete` 语句

* 插入多个行,每组值用一对圆括号括起来,用逗号分隔,单条 insert 语句插入多个值比多条 insert 语句快

* `insert select`  语句, `select` 中的第一列将来填充表列中指定的第一个列,第二列用来填充表列中指定的第二个列, `insert select` 中 `select` 语句可包含 `WHERE` 子句以过滤插入的数据

#### 更新数据

**update 语句非常容易使用，基本的 update 语句由 3 部分组成，要更新的表，列名和它们的新值，确定要更新行的过滤条件**

```sql
update table_name set table_filed = field_value where 
```

* 如果用 `update` 语句更新多行,并且在更新这些行中的一行或多行时出现一个错误,则整个 `update` 操作被取消(错误发生前更新的所有行被恢复到它们原来的值),如果指定即使发生错误,也继续进行更新,使用 `ignore` 关键字 `update ignore customers`
* 为了删除某个列的值,可设置它为 `null` 

#### 删除数据

```sql
delete from table_name where
```

* 更快的删除,如果想从表中删除所有行,使用 `truncate table` 更快

#### 使用 alter 修改表结构

```mysql
# 添加列
ALTER TABLE table_name ADD COLUMN column_name datatype
# 删除列
ALTER TABLE table_name DROP COLUMN column_name
# 修改列数据类型
ALTER TABLE table_name ALTER COLUMN column_name datatype
# 定义外键
ALTER TABLE table_name ADD CONSTRAINT foreign_key_name(外键名称) FOREIGN key(本表列名) REFERENCES 关联表名(关联表列名)
# 删除外键
ALTER TABLE table_name DROP FOREIGN key(外键名称)
# 重名名表
RENAME TABLE source_table_name TO target_table_name
# 索引创建
ALTER TABLE tb_name | ADD {INDEX|KEY} [index_name] [index_type] (index_col_name,...) [index_option]...
CREATE [UNIQUE] INDEX index_name [index_type] ON tb_name (index_col_name,...)
# 索引删除
ALTER TABLE tb_name DROP PARIMARY KEY | DROP {INDEX|KEY} index_name
DROP INDEX index_name ON tb_name
```

#### 视图

**虚拟表，只包含使用时动态检索数据的查询，因为视图不包含数据，所以每次使用视图时，都必须处理查询执行时候所需的任一个检索，如果用多个联结和过滤创建了视图或嵌套了视图，则性能会下降的厉害，视图规则与表基本一致**

* 创建视图 `create view vendorlocations as select concat(rtrim(vend_name), ' (', rtrim(vend_country), ')') as vend_title from vendors order by vend_name`

  
