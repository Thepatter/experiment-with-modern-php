*javax.xml.stream.XMLOutputFactory*
```java
static XMLOutputFactory newInstance();

// 返回 `XMLOutputFactory` 类的一个实例

XMLStreamWriter createXMLStreamWriter(OutputStream in);

XMLStreamWriter createXMLStreamWriter(OutputStream in, String characterEncoding);

XMLStreamWriter createXMLStreamWriter(Writer in);

XMLStreamWriter createXMLStreamWriter(Result in);

// 创建写出到给定流，写出器或 JAXP 结果的写出器

```