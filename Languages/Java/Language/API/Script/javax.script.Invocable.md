*javax.script.Invocable*
```java
Object invokeFunction(String name, Object... parameters);

Object invokeMethod(Object implicitParameter, String name, Object... explicitParameters);

// 用给定的名字调用函数或方法，并传递给定的参数

T> T getInterface(Class<T> iface);

// 返回给定接口的实现，该实现用脚本引擎中的函数实现了接口中的方法

T> T getInterface(Object implicitParameter, Class<T> iface);

// 返回给定接口的实现，该实现用给定对象的方法实现了接口中的方法

```