*javax.xml.parsers.SAXParser*
```java
void parse(File f, DefaultHandler handler);

void parse(String url, DefaultHandler handler);

void parse(InputStream in, DefaultHandler handler);

// 解析来自给定文件，URL 或输入流的 XML 文档，并把解析事件报告给指定的处理器

```