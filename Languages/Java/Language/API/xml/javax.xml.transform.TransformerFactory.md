*javax.xml.transform.TransformerFactory*
```java
static TransformerFactory newInstance();

// 返回 `TransformerFactory` 类的一个实例

Transformer newTransformer();

// 返回 `Transformer` 类的一个实例，它实现了标识符转换（不做任何事情的转换）

Transformer newTransformer(Source styleSheet);

// 返回一个 `transformer` 类的实例，用来从指定的源中读取样式表

```