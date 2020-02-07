*javax.script.ScriptEngineManager*
```java
List<ScriptEngineFactory> getEngineFactories();

// 获取所有发现的引擎工厂的列表

ScriptEngine getEngineByName(String name);

ScriptEngine getEngineByExtension(String extension);

ScriptEngine getEngineByMimeType(String mimeType);

// 获取给定名字、脚本文件扩展名或 `MIME` 类型的脚本引擎

Object get(String key);

void put(String key, Object value);

// 在全局作用域内获取或放置一个绑定

```