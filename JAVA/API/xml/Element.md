## org.w3c.dom.Element

* `String getTagName()`

  返回元素的名字

* `String getAttribute(String name)`

  返回给定名字的属性值，没有改属性时返回空字符串

* `void setAttribute(String name, String value)`

* `void setAttributeNS(String uri, String qname, String value)`

  将有给定名字的属性设置为指定的值

  `uri`		名字空间的 URI 或 null

  `qname`		限定名。如果有别名前缀，则 `uri` 不能为 `null`

  `value`		属性值