*javax.xml.transform.Transformer*
```java
void setOutputProperty(String name, String value);

// 设置输出属性。

// `doctype-public`		`DOCTYPE` 声明中使用的公共 ID

// `doctype-system`		`DOCTYPE` 声明中使用的系统 ID

// `Indent`				yes 或 no

// `method`				`xml` ，`html`，`text` 或定制的字符串

void transform(Source from, Result to);

// 转换一个 XML 文档

```