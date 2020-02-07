*java.sql.Savepoint*
```java
int getSavepointId();

// 获取该匿名保存点的 ID 号。如果该保存点具有名字，则抛出一个 `SQLException` 异常

String getSavepointName();

// 获取该保存点的名称。如果该对象为匿名保存点，则抛出一个 `SQLException` 异常

```