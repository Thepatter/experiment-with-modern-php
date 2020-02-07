*java.sql.ResultSetMetaData*
```java
int getColumnCount();

// 返回当前 `ResultSet` 对象中的列数

int getColumnDisplaySize(int column);

// 返回给定序列号的列的最大宽度

// `column`		序列号

String getColumnLable(int column);

// 返回该列所建议的名称

// column		列序号

String getColumnName(int column);

// 返回指定的列序号所对应的列名

// `column`		列序号

String getColumnName(int column);

// 返回指定的列序号所对应的列名

// `column`		列序号

```