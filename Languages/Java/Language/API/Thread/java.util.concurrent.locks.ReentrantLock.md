*java.util.concurrent.locks.ReentrantLock*
```java
ReetrantLock();

// 构建一个可以被用来保护临界区的可重入锁

ReetractLock(boolean fair);

// 构建一个带有公平策略的锁。一个公平锁偏爱等待时间最长的线程。但是，这一公平的保证将大大降低性能。在默认情况下，锁没有被强制为公平的

```