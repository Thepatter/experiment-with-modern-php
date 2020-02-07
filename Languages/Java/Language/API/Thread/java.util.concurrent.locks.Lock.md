*java.util.concurrent.locks.Lock*
```java
Condition newCondition();

// 返回一个与该锁相关的条件对象

void lock();

// 获取这个锁；如果锁同时被另一个线程拥有则发生阻塞

void unlock();

// 释放这个锁

boolean tryLock();

// 尝试获得锁而没有发生阻塞；如果成功返回真。这个方法会抢夺可用的锁，即使该锁有公平加锁策略，即便其他线程已经等待很久也是如此

boolean tryLock(long time, Timeout unit);

// 尝试获得锁，阻塞时间不会超过给定的值；如果成功返回 true

void lockInterruptibly();

// 获得锁，但是会不确定地发生阻塞。如果线程被中断，抛出 `InterruptedException` 异常

```