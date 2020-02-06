*javax.xml.parsers.DocumentBuilder*

```java
// 解析给定文件，返回解析后的文档
Document parse(File f) throws SAXException, IOException;
// 解析 URL，返回解析后的文档
Document parse(String url) throws SAXException, IOException;
// 解析输入流，返回解析后的文档
Document parse(InputStream is) throws SAXException, IOException;
// 设置解析器，来定义要解析的 XML 文档中引用的实体
void EntityResolver(EntityResolver var1);
// 设置用来报告在解析过程中出现的错误和警告的处理器
void setErrorHandler(ErrorHandler var1);
// 返回一个空文档
Document newDocument();
```

