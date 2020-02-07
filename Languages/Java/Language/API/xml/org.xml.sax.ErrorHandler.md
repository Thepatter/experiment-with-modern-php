*org.xml.sax.ErrorHandler*
```java
void fatalError(SAXParseException exception);

void error(SAXParseException exception);

void warning(SAXParseException exception);

// 覆盖这些方法以提供对致命错误，非致命错误和警告进程处理的处理器

```