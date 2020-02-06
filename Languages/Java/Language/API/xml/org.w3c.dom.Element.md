*org.w3c.dom.Element*

```java
// 返回元素的名字
String getTagName();
// 返回给定名字的属性值
String getAttribute(String var1);
/**
 * 将给定名字的属性设置为指定的值
 * @param uri 命名空间的 URI
 * @qname 限定名
 * @value 属性值
 */ 
void setAttribute(String name, String value) throws DOMException;
void setAttributeNS(String uri, String qname, String value) throws DOMException;
```