*javax.xml.parsers.DocumentBuilderFactory*

```java
// 返回 DocumentBuilderFactory 实例
static DocumentBuilderFactory newInstance();
// 返回 DocumentBuilder 类的一个实例
DocumentBuilder newDocumentBuilder();
// 获取 namespaceAware 属性
boolean isNamespaceAware();
// 设置 namespaceAware 属性，当为 true 时，工厂产生的解析器是命名空间感知的
void setNamespaceAware(boolean value);
```

