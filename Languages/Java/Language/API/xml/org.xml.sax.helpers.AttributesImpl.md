*org.xml.sax.helpers.AttributesImpl*
```java
void addAttribute(String uri, String lname, String qname, String type, String value);

// 将一个属性添加到该属性集合

// `uri`		名字空间的 URI

// `lname`		无前缀的本地名

// `qname`		带前缀的限定名

// `typ`		类型，`CDATA`，`ID`，`IDREF`，`IDREFS`，`NMTOKEN`，`NMTOKENS`，`ENTITY`，`ENTITIES`，`NOTATION` 之一的属性值

void clear();

// 删除属性集合中的所有属性

```