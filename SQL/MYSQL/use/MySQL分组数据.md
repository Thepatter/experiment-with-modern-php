### Mysql 分组数据

#### 数据分组 `GROUP BY`

* 创建分组：分组是在 `SELECT` 语句的 `GROUP BY` 子句中建立的。
* `GROUP BY` 子句可以包含任意数目的列。这使得能对分组进行嵌套。
* 如果在 `GROUP BY` 子句中嵌套了分组，数据将在最后规定的分组上进行汇总。在建立分组时候，指定的所有列都一起计算（所以不能从个别的列取回数据）
* `GROUP BY` 子句中列出的每个列都必须是检索列或有效的表达式（但不能是聚集函数）。如果在 `SELECT` 中使用表达式，则必须在 `GROUP BY` 子句中指定相同的表达式。不能使用别名
* 除聚合计算语句外，`SELECT` 语句中的每个列都必须在 `GROUP BY` 子句中给出
* 如果分组列中具有 `NULL` 值，则将 `NULL` 作为一个分组返回。如果列中有多行 `NULL` 值，它们将分为一组
* `GROUP BY` 子句必须出现在 `WHERE` 子句之后，`ORDER BY` 子句之前

#### 过滤分组 `HAVING` 

**规定包括那些分组，排除那些分组, where 过滤行，having 过滤分组，where 与 having 语法相同，where 在数据分组前进行过滤，having 在数据分组后进行过滤，where 排除的行不包括在分组中。**

#### `SELECT` 子句顺序

|   子句    |        说明        |          是否必须          |
| :-------: | :----------------: | :------------------------: |
|  SELECT   | 要返回的列或表达式 |             是             |
|   FROM    |  从中检索数据的表  |  仅在从表中选择数据时使用  |
|   WHERE   |      行级过滤      |             否             |
| GROUP BY  |      分组数据      | 仅在按组计算集合数据时使用 |
|  HAVING   |      分组过滤      |             否             |
| ORDER　BY |    输出排序顺序    |             否             |
|   LIMIT   |    要检索的行数    |             否             |

#### 子查询

* 在 `select` 语句中子查询总是从内向外执行
* 可以将计算字段作为子查询
* 子查询中使用完全限定的列名来避免歧义

#### 联结 `join`

**联结是一种机制,用来在一条 select 语句中关联表,使用特殊的语法,可以联结多个表返回一组输出,联结在运行时关联表中正确的行,联结不是物理实体,它存在于查询的执行当中**

* 外键为某个表中的一列,它包含另一个表的主键值

* 笛卡尔积,由没有联结条件的表关系返回的结果为笛卡尔积.检索出的行的数目将是第一个表中的行数乘以第二个表中行数
* 内连接:基于两个表之间的等值联结 `select vend_name, prod_name, prod_price from vendors Inner join products on vendors.vend_id = products.vend_id`
* sql 对一条 select 语句中可以联结的表的数目没有限制,创建联结的基本规则也相同
* MySQL 在运行时关联指定的每个表以处理联结,这种处理可能非常耗费资源,不要联结不必要的表,联结的表越多,性能下降越厉害
* 使用表别名可以缩短SQL语句,允许在单条select语句中多次使用相同的表
* 外部联结的类型:左外部联结和右外部联结.它们之间的唯一区别是所关联的表的顺序不同,左外部联结可通过颠倒FROM或WHERE子句中表的顺序转换为右外部联结.

#### 使用联结和联结条件

* 一般我们使用内部联结,但使用外部联结也是有效
* 应该总是提供联结条件,否则会得出笛卡尔积

#### 组合查询 `UNION`

**MySQL 允许执行多个查询(多条 SELECT 语句),并将结果作为单个查询结果集返回,这些组合查询通常称为并(union)或符合查询**

* 在单个查询中从不同的表返回类似结构的数据
* 对单个表执行多个查询,按单个查询返回数据

* 可用 `UNION` 操作符来组合数条 SQL 查询,利用 UNION,可给出多条 SELECT 语句,将它们的结果组合成单个结果集
* `UNION` 的使用很简单,所需做的只是给出每条 `select` 语句,在各条语句之间放上关键字 `UNION` `SELECT vend_id, prod_id, prod_price FROM products where prod_price <=5 UNION select vend_id, prod_id, prod_price FROM products where vend_id IN (1000, 10002)`
* `UNION` 必须由两条或两条以上的 `select` 语句组成,语句之间用关键字 `UNION` 分隔(如果组合 4 条 select 语句,将要使用 3个 UNION 关键字)
* `UNION` 中的每个查询必须包含相同的列,表达式或聚合函数(不过各个列不需要以相同的次序列出)
* 列数据类型必须兼容:类型不必完全相同,但必须是 `DBMS` 可以隐含地转换的类型
* `UNION` 从查询结果集中自动去除了重复的行(换句话说,它的行为与单条 `SELECT` 语句中使用多个 `WHERE` 子句条件一样),如果需要返回所有匹配行,可使用 `UNION ALL`

* 对组合查询结果排序时只能使用一条 `order by` 子句,它必须出现在最后一条SELECT语句之后,对于结果集,不存在用一种方式排序一部分,而又用另一种方式排序另一部分的情况,因此不允许使用多条 `order by` 子句
* `UNION` 的组合查询可用应用不同的表

#### 全文本搜索

* MySQL 的 `MyISAM` 引擎支持全文本搜索

* 为了进行全文本搜索,必须索引被搜索的列,而且要随着数据的改变不断地重新索引.在对表列进行适当的设计后,MySQL 会自动进行所有的索引和重新索引,在索引之后, `SELECT` 可与 `Match()` 和 `Against()` 一起使用以实际执行搜索

* 一般在创建表时启用全文本搜索.`CREATE TABLE` 语句接受 `FULLTEXT` 子句,它给出被索引列的一个逗号分隔的列表

  ```sql
  CREATE TABLE productnots
  (
  	note_id int NOT NULL AUTO_INCREMENT,
  	prod_id char(10) NOT NULL,
  	note_date datetime NOT NULL,
  	note_text text null,
  	PRIMAY KEY(note_id),
  	FULLTEXT(note_text)
  ) ENGINE=MyISAM;
  ```

  定义后,MySQL 自动维护该索引,在增加,更新或删除时,索引随之自动更新

* 在索引之后,使用两个函数 `Match()` 和 `Against()` 执行全文本搜索,其中 `Match()` 指定被搜索的 列,`Against()` 指定要使用的搜索表达式,传递给 `Match()` 的值必须与 `FULLTEXT` 定义中的相同.如果指定多个列,则必须列出它们(而且次序正确),除非使用 `BINARY` 方式,否则全文搜索不区分大小写

  `select note_text From productnotes where Match(note_text) Against('rabbit')`

* 使用查询扩展:查询扩展用来设法放宽所返回的全文本搜索结果的范围,利用查询扩展,能找出可能相关的结果,即使它们并不精确包含所查找的词

  `select note_text from productnotes where match(note_text) against('anvils' with query expansion)`

#### 布尔文本搜索

支持全文本搜索的另外一种形式.布尔方式提供下面的细节:

* 要匹配的词

* 要排斥的词(如果某行包含这个词,则不返回该行,即使它包含其他指定的词也是如此)

* 排序提示(指定某些词比其他词更重要,更要的词等级更高)

* 表达式分组
* 即使没有 `FULLTEXT` 索引也可以使用, 但这是一种非常缓慢的操作,其性能将随着数据量的增加而降低

   **全文本布尔操作符**

| 布尔操作符 |                             说明                             |
| :--------: | :----------------------------------------------------------: |
|     +      |                       包含，词必须存在                       |
|     -      |                      排除，词必须不出现                      |
|     >      |                     包含，而且增加等级值                     |
|     <      |                      包含，且减少等级值                      |
|     ()     | 把词组成子表达式(允许这些子表达式作为一个组被包含,排除,排列等) |
|     ~      |                       取消一个词的排序                       |
|     *      |                         词尾的通配符                         |
|     ""     | 定义一个短语(与单个词的列表不一样，它匹配整个短语以便包含或排除这个短语) |

`where match(note_text) against('+rabbit +bait' IN BOOLEAN MODE)` 搜索匹配包含词 `rabbit` 和 `bait`的行

`where match(note_text) against('rabbit bait' IN BOOLEAN MODE)` 搜索匹配包含 `rabbit` 和 `bait` 中至少一个词的行

`where match(note_text) against('"rabbit bait"' IN BOOLEAN MODE)` 搜索匹配短语 `rabbit bait` 而不是两个词

`where match(note_text) against('>rabbit <carrot' in boolean mode)` 匹配 `rabbit` 和 `carrot` ，增加前者的等级，降低后者的等级

`where match(note_text) against('+safe +(<combination)' IN BOOLEAN MODE)` 搜索匹配词 `safe` 和 `combination`，降低后者的等级

* 在布尔方式中，不按等级值降序排序返回的行

* 在索引全文本数据时，短词被忽略且从索引中排除，短词定义为那些具有3个或3个字符的词(如果需要，这个数目可以更改)
* `MySQL` 带有一个内建的非用词(`stopword`) 列表，这些词在索引全文本数据时总是被忽略，如果需要，可以覆盖这个列表
* 许多词出现的频率很高，搜索它们没有用处，因此`MySQL`规定了一条50%规则，如果一个词出现在50%以上的行中，则将它作为一个非用词忽略。50%规则不用于`in boolean mode`
* 如果表中的行少于3行，则全文本搜索不返回结果(因为每个词或者不出现,或者至少出现在50%的行中)
* 忽略词中的单引号
* 不具有词分隔符(包括日语和汉语)的语言不能恰当地返回全文本搜索结果
* 仅在 `MyISAM` 数据库引擎中支持全文本搜索