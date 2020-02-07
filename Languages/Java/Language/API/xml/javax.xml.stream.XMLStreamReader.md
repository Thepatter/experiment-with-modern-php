*javax.xml.stream.XMLStreamReader*
```java
boolean hasNext();

// 如果有另一个解析事件，则返回 true

int next();

// 将解析器的状态设置为下一个解析事件，并返回下列常量之一 `START_ELEMENT`，`END_ELEMENT`，`CHARACTERS`，`START_DOCUMENT`，`END_DOCUMENT`，`CDATA`，`COMMENT`，`SPACE`，`PROCESSING_INSTRUCTION`，`ENTITY_PEFERENCE`，`DTD`

boolean isStartElement();

boolean isEndElement();

boolean isCharacters();

boolean isWhiteSpace();

// 如果当前事件是一个开始元素，结束元素，字符数据或空白字符，则返回 `true`

QName getName();

String getLocalName();

// 获取在 `START_ELEMENT` 或 `END_ELEMENT` 事件中的元素的名字

String getText();

// 返回一个 `CHARACTERS`，`COMMENT` 或 `CDATA` 事件中的字符，或一个 `ENTITY_REFERENCE` 的替换值，或者一个 DTD 的内部子集

int getAttributeCount();

QName getAttributeName(int index);

String getAttributeLocalName(int index);

String getAttributeValue(int index);

// 如果当前事件是 `START_ELEMENT`，则获取属性数量和属性的名字与值

String getAttributeValue(String namespaceURI, String name);

// 如果当前事件是 `START_ELEMENT`，则获取具有给定名称的属性的值。如果 `namespaceURI` 为 null，则不检查命名空间。

```