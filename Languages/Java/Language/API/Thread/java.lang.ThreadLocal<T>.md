*java.lang.ThreadLocal<T>*
```java
T get();

// 得到这个线程的当前值。如果是首次调用 `get`，会调用 `initialize` 来得到这个值

protedcted ininialize();

// 应覆盖这个方法来提供一个初始值。默认情况下，返回 null

void set(T t);

// 为这个线程设置一个新值

void remove();

// 删除对应这个线程的值

static <S> ThreadLocal<S> withInitial(Supplier<? extends s> supplier);

// 创建一个线程局部变量，其初始值通过调用给定的 `supplier` 生成

```