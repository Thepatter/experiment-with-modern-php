## javax.xml.parsers.DocumentBuilder

* `Document parse(File f)`

* `Document parse(String url)`

* `Document parse(InputStream in)`

  解析来自给定文件，URL 或输入流的 XML 文档，返回解析后的文档

* `void setEntityResolver(EntityResolver resolver)`

  设置解析器，来定位要解析的 `XML` 文档中引用的实体

* `void setErrorHandler(ErrorHandler handler)`

  设置用来报告在解析过程中出现的错误和警告的处理器

* `Document newDocument()`

  返回一个空文档

  