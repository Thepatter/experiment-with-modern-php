*java.sql.DataTruncation*
```java
boolean getParameter();

// 如果在参数上进行了数据截断，则返回 `true`；如果在列上进行了数据截断，则返回 `false`

int getIndex();

// 返回被截断的参数或列的索引

int getDataSize();

// 返回应该被传输的字节数量，或者在该值未知的情况下返回 -1

int getTransferSize();

// 返回实际被传输的字节数量，或者在该值未知的情况下返回 -1

```