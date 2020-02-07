*java.sql.Blob*
```java
long length();

// 获取该 `BLOB` 的长度

byte[] getBytes(long startPosition, long length);

// 获取该 `BLOB` 中给定范围的数据

InputStream getBinaryStream();

InputStream getBinaryStream(long startPosition, long length);

// 返回一个输入流，用于读取该 `BLOB` 中全部或给定范围的数据

OutputStream setBinaryStream(long startPosition);

// 返回一个输出流，用于从给定位置开始写入该 `BLOB`

```