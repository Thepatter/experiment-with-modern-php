*javax.sql.rowset.RowSetProvider*
```java
static RowSetFactory newFactory();

// 创建一个行集工厂

CachedRowSet createCachedRowSet();

FilteredRowSet createFilteredRowSet();

JdbcRowSet createJdbcRowSet();

JoinRowSet createJoinRowSet();

WebRowSet createWebRowSet();

// 创建一个指定类型的行集

```