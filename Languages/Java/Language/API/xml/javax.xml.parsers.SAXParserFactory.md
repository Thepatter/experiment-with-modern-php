*javax.xml.parsers.SAXParserFactory*
```java
* `static SAXParserFactory`
// 返回 `SAXParserFactory` 类的一个实例

SAXParser newSAXParser();

// 返回 `SAXParser` 类的一个实例

boolean isNamespaceAware();

void setNamespaceAware();

// 获取和设置工厂的 `namespaceAware` 属性。当设为 `true` 时，该工厂生成的解析器是命名空间感知的

boolean isValidating();

void setValidating(boolean value);

// 获取和设置工厂的 `validating` 属性。当设为 `true` 时，该工厂生成的解析器将要验证其输入

```