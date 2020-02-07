*org.xml.sax.EntityResolver*
```java
public InputSource resolveEntiry(String publicID, String systemID);

// 返回一个输入流，它包含了被给定 ID 所引用的数据，或者，当解析器不知道如何解析这个特定名字时，返回 null。如果没有提供公共 ID ，那么参数 `publicID` 可为 null

```