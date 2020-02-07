*org.xml.sax.Attributes*
```java
int getLength();

// 返回存储在该属性集合中的属性数量

String getLocalName(int index);

// 返回给定索引的属性的本地名（无前缀），或在解析器不是命名空间感知的情况下返回空字符串

String getURI(int index);

// 返回给定索引的属性的命名空间 URI，或者，当该节点不是命名空间的一部分，或解析器并非命名空间感知时返回空字符串

String getQName(int index);

// 返回给定索引的属性的限定名（带前缀），或当解析器不报告限定名时返回空字符串

String getValue(int index);

String getValue(String qname);

String getValue(String uri, String lname);

// 根据给定索引，限定名或命名空间 URI + 本地名来返回属性值；当该值不存在时，返回 null

```