*java.sql.Clob*
```java
long length();

// 获取该 `CLOB` 中的字符总数

String getSubString(long startPosition, long length);

// 获取该 `CLOB` 中给定范围的字符

Reader getCharacterStream();

Reader getCharacterStream(long startPosition, long length);

// 返回一个读入器（而不是流），用于读取 `CLOB` 中全部或给定范围的数据

Writer setCharacterStream(long startPosition);

// 返回一个写出器（而不是流），用于从给定位置开始写入该 `CLOB`

```