*org.xml.sax.ContentHandler*
```java
void startDocument();

void endDocument();

// 在文档的开头和结尾处被调用

void startElement(String uri, String lname, String qname, Attributes attr);

// 在元素的开头和结尾处被调用

// `uri`		命名空间的 URI （如果解析器是命名空间感知的）

// `lname`		不带前缀的本地名（如果解析器是命名空间感知的）

// `qname`		元素名（如果解析器是命名空间感知的），或者是带有前缀的限定名

void characters(char[] data, int start, int length);

// 解析器报告字符数据时被调用

// `data`		字符数据数组

// `start`		在作为被报告的字符数据的一部分的字符数组中，第一个字符的索引

// `length`		被报告的字符串的长度

```