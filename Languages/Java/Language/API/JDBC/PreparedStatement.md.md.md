*PreparedStatement.md.md*
```java
```java
```java
// // //（Xxx 指 int、double、String、Date 之类的数据类型）设置第 n 个参数值为 x。

void setXxx(int n, Xxx x);

// // // 清除预备语句中的所有当前参数

void clearParameters();

// // // 执行预备 SQL 查询，并返回一个 ResultSet 对象

ResultSet executeQuery();

// // // 执行预备 SQL 语句 INSERT、UPDATE、DELETE ，这些语句由 PreparedStatement 对象表示。该方法返回在执行上述语句过程中所有受影响的记录总数。如果执行的是数据定义 DDL 语句。该方法返回 0

int executeUpdate();

```
```
```