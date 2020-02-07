*java.lang.System*
```java
static Console concole();

// 如果有可能进行交互操作，就通过控制台窗口为交互的用户返回一个 `Console` 对象，否则返回 `null`。对于任何一个通过控制台窗口启动的程序，都可以使用 Console 对象。

// 否则其可用性将与所使用的系统有关

static int identityHashCode(Object obj);

// 返回 `Object.hashCode` 计算出来的相同散列码（根据对象内存地址产生），即使 `obj` 所属的类已经重新定义了 `hashCode` 方法也是如此

```