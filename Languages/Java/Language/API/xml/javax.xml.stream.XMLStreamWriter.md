*javax.xml.stream.XMLStreamWriter*
```java
void writeStartDocument();

void writeStartDocument(String xmlVersion);

void writeStartDocument(String encoding, String xmlVersion);

// 在文档的顶部写入 XML 处理指令。注意，`encoding` 参数只是用于写出这个属性，它不会设置输出的字符编码机制

void setDefaultNamespace(String namespaceURI);

void setPrefix(String prefix, String namespaceURI);

// 设置默认的命名空间，或者具有前缀的命名空间。这种声明的作用域只是当前元素，如果没有写明具体元素，其作用域为文档的根

void writeStartElement(String localName);

void writeStartElement(String namespaceURI, String localName);

// 写出一个开始标签，其中 `namespaceURI` 将用相关联的前缀来代替

void writeEndElement();

// 关闭当前元素

void writeEndDocument();

// 关闭所有打开的元素

void writeEmptyElement(String localName);

void writeEmptyElement(String namespaceURI, String localName);

// 写出一个自闭合的标签，其中 `namespaceURI` 将用相关联的前缀来代替

void writeAttribute(String localName, String value);

void writeAttribute(String namespaceURI, String localName, String value);

// 写出一个用于当前元素的属性，其中 `namespaceURI` 将用相关联的前缀来代替

void writeCharacters(String text);

// 写出字符数据

void writeCData(String text);

// 写出 `CDATA` 块

void writeDTD(String dtd);

// 写出 `dtd` 字符串，该字符串需要包含一个 `DOCTYPE ` 声明

void writeComment(String comment);

// 写出一个注释

void close();

// 关闭这个写出器

```