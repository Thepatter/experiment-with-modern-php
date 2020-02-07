*java.util.concurrent.BlockingDeque<E>*
```java
void putFirst(E element);

void putLast(E element);

// 添加元素，必要时阻塞

E takeFirst();

E takeLast();

// 移除并返回头元素或尾元素，必须时阻塞

boolean offerFirst(E element, long time, TimeUnit unit);

boolean offerLast(E element, long time, TimeUnit unit);

// 添加给定的元素，成功时返回 true，必要时阻塞直至元素被添加或超时

E pollFirst(long time, TimeUnit unit);

E pollLast(long time, TimeUnit unit);

// 移动并返回头元素或尾元素，必要时阻塞，直至元素可用或超时。失败时返回 null

```