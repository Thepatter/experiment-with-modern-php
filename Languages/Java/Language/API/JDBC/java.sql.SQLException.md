*java.sql.SQLException*
```java
SQLException getNextException();

// 返回该链接到该 `SQL` 异常的下一个 `SQL` 异常，或者在到达链尾时返回 null

Iterator<Throwable> iterator();

// 获取迭代器，可以迭代链接的 `SQL` 异常和它们的成因

String getSQLState();

// 获取 "SQL 状态"，即标准化的错误代码

int getErrorCode();

// 获取提供商相关的错误代码

```