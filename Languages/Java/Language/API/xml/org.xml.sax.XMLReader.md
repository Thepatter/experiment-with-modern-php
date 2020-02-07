*org.xml.sax.XMLReader*
```java
void setContentHandler(ContentHandler handler);

// 设置在输入流被解析时会被告知解析事件的处理器

void parse(InputSource source);

// 解析来自给定输入源的输入数据，并将解析事件发送到内容处理器

```