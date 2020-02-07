*javax.script.ScriptEngine*
```java
Object eval(String script);

Object eval(Reader reader);

Object eval(String script, Bindings binginds);

Object eval(Reader reader, Bindings bindings);

// 对由字符串或读取器给定的脚本赋值，并服从给定的绑定

Object get(String key);

void put(String key, Object value);

// 在引擎作用域内获取或放置一个绑定

Bindings createBindings();

// 创建一个适合该引擎的空 `Bindings` 对象

ScriptContext getContext();

// 获得该引擎的默认的脚本上下文

```