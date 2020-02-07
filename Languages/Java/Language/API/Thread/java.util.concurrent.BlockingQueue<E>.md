*java.util.concurrent.BlockingQueue<E>*
```java
void put(E element);

// 添加元素，在必要时阻塞

E take();

// 移除并返回头元素，必要时阻塞

boolean offer(E element, long time, TimeUnit unit);

// 添加给定的元素，如果成功返回 true，如果必要时阻塞，直至元素被添加或超时

E poll(long time, TimeUnit unit);

// 移除并返回头元素，必要时阻塞，直至元素可用或超时用完。失败时返回 null

```