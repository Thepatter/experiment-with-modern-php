*org.w3c.dom.Node*

```java

```



* `NodeList getChildNodes()`

  返回包含该节点所有子元素的节点列表

* `Node getFirstChild()`

* `Node getLastChild()`

  获取该节点的第一个或最后一个子节点，在该节点没有子节点时返回 null

* `Node getNextSibling()`

* `Node getPreviousSibling()`

  获取该节点的下一个或上一个兄弟节点，在该节点没有兄弟节点时返回 null

* `Node getParentNode()`

  获取该节点的父节点，在该节点是文档节点时返回 null

* `NamedNodeMap getAttributes()`

  返回含有描述该节点所有属性的 `Attr` 节点的映射表

* `String getNodeName()`

  返回该节点的名字，当该节点是 `Attr` 节点时，该节点名字就是属性名

* `String getNodeValue()`

  返回该节点的值。当该节点是 `Attr` 节点时，该值就是属性值

* `String getLocalName()`

  返回本地名（不带前缀），或者在解析器不感知命名空间时，返回 null

* `String getNamespaceURI()`

  返回命名空间 URI，或者在解析器不感知命名空间时，返回 null

* `Node appendChild(Node child)`

  在该节点的子节点列表中追加一个节点。返回被追加的节点