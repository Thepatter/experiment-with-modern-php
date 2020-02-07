*java.beans.EventHandler*
```java
static <T> T create(Class<T> listenerInterface, Object targer, String action);

static <T> T create(Class<T> listenerInterface, Object target, String action, String eventProperty);

static <T> T create(Class<T> listenerInterface, Object target, String action, String eventProperty, String listenerMethod);

// 构造实现给定接口的一个代理类的对象。接口的指定方法或所有方法会在目标对象上执行给定的动作

```