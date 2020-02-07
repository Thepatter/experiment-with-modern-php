*javax.xml.xpath.XPath*
```java
String evaluate(String expression, Object startingPoint);

// 从给定的起点计算表达式。起点可以是一个节点或节点列表。如果结果是一个节点或节点集，则返回的字符串由所有文本节点子元素的数据构成

Object evaluate(String expression, Object startingPoint, OName resultType);

// 从给定的起点计算表达式。起点可以是一个节点或节点列表。`resultType` 是 `XPathConstants` 类的常量 `STRING`，`NODE`，`NODESET`，`NUMBER` ，`BOOLEAN` 之一。返回值是 `String`，`Node`，`NodeList`，`Number`，`Boolean`

```