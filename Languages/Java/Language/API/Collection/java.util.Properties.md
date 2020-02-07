*java.util.Properties*
```java
Properties();

// 创建一个空的属性映射

Properties(Properties defaults);

// 创建一个带有一组默认值的空的属性映射

String getProperty(String key);

// 获得属性的对应关系；返回与键对应的字符串。如果在映射中不存在，返回默认表中与这个键对应的字符串

String getProperty(String key, String defaultValue);

// 获得在键没有找到时具有的默认值属性；它将返回与键对应的字符串，如果在映射中不存在，就返回默认的字符串

void load(InputStream in);

// 从 `InputStream` 加载属性映射

void store(OutputStream out, String commentString);

// 把属性映射存储到 `OutputStream`

```