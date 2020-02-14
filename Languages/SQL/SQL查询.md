## SQL 查询

### SQL 执行流程

```sql
SELECT DISTINCT player_id, player_name, count(*) as num # setop5
FROM player JOIN team ON player.team_id = team.team_id # setop1
WHERE height > 1.80 # setop2
GROUP BY player.team_id # setop3
HAVING num > 2 # setop 4
ORDER BY num DESC # setop  6
LIMIT 2 # setop 7
```

SELECT 是先执行 FROM 这一步的。在这个阶段，如果是多张表联合查询，还会经历以下几个步骤

* 首先通过 `CROSS JOIN` 求笛卡尔积，相当于得到虚拟表 `vt1-1`
* 通过 ON 进行筛选，在虚拟表 `vt1-1` 的基础上进行筛选，得到虚拟表 `vt1-2`
* 添加外部行。如果使用的是左链接，右链接或者全链接，就会涉及到外部行，即在虚拟表 `vt1-2` 的基础上增加外部行 ，得到虚拟表 `vt1-3`
* 直到所有表被都被处理完为止

这个过程得到原始数据，即最终的虚拟表 `vt1`，就可以在此基础上再进行 `WHERE` 阶段。在这个阶段中，会根据 `vt1` 表的结果进行筛选过滤，得到虚拟表 `vt2`；然后进行 `setop3` 和 `setop4` ，`GROUP` 和 `HAVING` 阶段。在这个阶段中，实际上实在虚拟表 `vt2` 的基础上进行分组和分组过滤的，得到中间的虚拟表 `vt3` 和 `vt4`；完成条件筛选部分之后，就可以筛选表中提取的字段，进入到 `SELECT` 和 `DISTINCT` 阶段；首先在 `SELECT` 阶段会提取想要的字段，然后在 `DISTINCT` 阶段过滤掉重复的行，分别得到中间的虚拟表 `vt5-1` 和 `vt5-2` ；提取到想要的字段数据之后，就可以按照指定的字段进行排序，也就是 `ORDER BY` 阶段，得到虚拟表 `vt6`；最后在 `vt6` 的基础上，取出指定行的记录，就是 `LIMIT` 阶段，得到最终的结果，对应虚拟表 `vt7`

在写 SELECT 语句的时候，不一定存在所有的关键字，相应的阶段就会省略

### 查询语句

select 语句有几个组件或者说子句构成，在 MYSQL 中只有 select 子句是必不可少的,

| 子句名称 |                   使用目的                   |
| :------: | :------------------------------------------: |
|  select  |          确定结果集中应该包含那些列          |
|   from   | 指明所要提取数据的表，以及这些表是如何连接的 |
|  where   |               过滤不需要的数据               |
| group by |        用于对具有相同列值的行进行分组        |
|  having  |               过滤掉不需要的组               |
| order by | 按一个或多个列，对最后的结果集中的行进行排序 |

### select 子句

select 子句在数据库服务中是最后被评估的,因为在确定结果集最后包含那些列之前,必须先要知道结果集所有可能包含的列

select 子句用于在所有可能的列中,选择查询结果集要包含那些列.可以在 select 子句中

* 字符,比如数字或字符串
* 表达式,比如 transaction.amount*-1
* 调用内建函数,如 ROUND(transaction.amount.2);
* 用户自定义函数调用

##### 列的别名

通过在 select 子句中的每个元素后面增加列别名可以实现此目的，也可以用 `AS` 关键字来实现

##### 去除重复的行

在 select 关键字之后加上 distinct 关键字来去除重复的行(产生无重复的结果集需要首先对数据排序，这对于大的结果集来说相当耗时，应该先了解所使用的数据是否可能包含重复行。以减少 DISTINCT 的不必要的使用)

### from 子句

from 子句定义了查询中所使用的表，以及连接这些表的方式

表包含永久表(使用 create table 语句创建的表)，临时表(子查询所返回的表)，虚拟表(使用 create view 子句所创建的视图)

子查询产生的表

子查询指的是包含在另一个查询中的查询，子查询可以出现在 select 语句中的各个部分并且被包含在圆括号中，在 from 子句内，子查询的作用是根据其他查询子句(其中的 from 子句可以与其他表进行交互) 产生临时表

```sql
SELECT e.emp_id, e.fname, e.lname FROM (SELECT emp_id, fname, lname, start_date, title FROM employee) e;
```

 ##### 视图

视图是存储在数据字典中的查询，它的行为表现得像一个表，但实际上并不拥有任何数据，当发出一个对视图的查询时，该查询会被绑定视图定义，以产生最终被执行的查询。创建视图可能出于各种理由，比如对用户隐藏列，简化数据库设计

表连接

如果 from 子句中出现了多个表，那么要求同时包含各表之间的连接条件

定义表别名

当在单个查询中连接多个表时，需要在 select, where, group by, having 以及 order by 子句中指明所引用的是那个表，有两种在 from 子句之外引用表的方式

* 使用完整的表名称，如 employee.emp_id;
* 为每个表指定表名，并在查询中需要的地方使用该别名

##### where 子句

where 子句用于在结果集中过滤掉不需要的行

##### group by 和 having 子句

group by 用于根据列值对数据进行分组，having 子句对分组数据进行过滤

```mysql
SELECT d.name, count(e.emp_id) num_employees FROM department d INNER JOIN employee e ON d.dept_id = e.dept_id GROUP BY d.name HAVING count(e.emp_id) > 2;
```

##### order by 子句

order by 子句用于对结果集中的原始数据或是根据列数据计算的表达式结果进行排序，使用 ASC 和 DESC 指定是升序还是降序，默认升序

根据表达式排序 `ORDER BY RIGHT(fed_id, 3);` 

根据查询返回的第 2 个和第 5 个列排序 `ORDER BY 2, 5`

