*org.xml.parsers.DocumentBuilderFactory*
```java
boolean isValidating();

void setValidating(boolean value);

// 获取和设置工厂的 `validating` 属性。当它设为 `true` 时，该工厂生成的解析器会验证它们的输入信息

boolean isIgnoringElementContentWhitespace();

void setIgnoringElementContentWhitespace(boolean value);

// 获取和设置工厂的 `igoringElementContentWhitespace` 属性。当它设为 `true` 时，该工厂生成的解析器会忽略不含混合内容（即：元素与 `#PCDATA` 混合）的元素节点之间的空白字符

```