*javax.sql.rowset.CachedRowSet*
```java
void execute(Connection conn);

// 通过执行使用 `setCommand` 方法设置的语句集来填充行集。该方法使用给定的连接，并负责关闭它

void populate(ResultSet result);

// 将指定的结果集中的数据填充到被缓存的行集中

* `String getTableName`
void setTableName(String tableName);

// 获取或设置数据库表名称，填充被缓存的行集时所需的数据来自该表

int getPageSize();

void setPageSize(int size);

// 获取和设置页的尺寸

boolean nextPage();

boolean previousPage();

// 加载下一页或上一页，如果要加载的页存在，则返回 `true`

void acceptChanges();

void acceptChanges(Connection conn);

// 重新连接数据库，并写回行集中修改过的数据。如果因为数据库中的数据已经被修改而导致无法写回行集中的数据，该方法可能会抛出 `SyncProviderException` 异常

```