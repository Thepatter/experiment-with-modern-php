*java.util.concurrent.TransferQueue<E>*
```java
void transfer(E element);

boolean tryTransfer(E element, long time, TimeUnit unit);

// 传输一个值，或者尝试在给定的超时时间内传输这个值，这个调用将阻塞，直到另一个线程将元素删除。第二个方法会在调用成功时返回 true

```