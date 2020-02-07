*javax.xml.stream.XMLInputFactory*
```java
static XMLInputFactory newInstance();

// 返回 `XMLInputFactory` 类的一个实例

void setProperty(String name, Object value);

// 设置这个工厂的属性，或者在要设置的属性不支持设置成给定值时，抛出 `IllegalArgumentException`  `Java SE` 的实现支持下列 `Boolean` 类型的属性

// `javax.xml.stream.isValidating`		为 `false` 默认值时，不验证文档

// `javax.xml.stream.isNamespaceAware`			为 `true` 默认值时，将处理命名空间（规范不要求必须支持）

// `javax.xml.stream.isCoalescing`		为 `false` 默认值时，邻近的字符数据被替换和报告

// `javax.xml.stream.isReplacingEntityReferences`		为 `true` 默认值时，实体引用将作为字符数据被替换和报告

// `javax.xml.stream.isSupportingExternalEntities`		为 `true` 默认值时，外部实体将被解析。规范对于这个属性没有给出默认值

// `javax.xml.stream.supportDTD`		为 `true` 默认值时，`DTD` 将作为事件被报告

XMLStreamReader createXMLStreamReader(InputStream in);

XMLStreamReader createXMLStreamReader(InputStream in, String characterEncoding);

XMLStreamReader createXMLStreamReader(Reader in);

XMLStreamReader createXMLStreamReader(Source in);

// 创建一个从给定的流，阅读器或 JAXP 源读入的解析器

```