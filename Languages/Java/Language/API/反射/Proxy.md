*java.lang.reflect.Proxy*

```java
// 返回实现指定接口的代理类
static Class<?> getProxyClass(ClassLoader Loader, Class<?>... interfaces);
// 狗子实现指定接口的代理类的一个新实例，所有方法会调用给定处理器对象的 invoke 方法
static Object newProxyInstance(ClassLoader loader, Class<?>[]interfaces, InvocationHandler handler);
// 如果 cl 是一个代理类则返回 true
static boolean isProxyClass(Class<?> cl)
```