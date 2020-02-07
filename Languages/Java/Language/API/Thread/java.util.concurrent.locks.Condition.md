*java.util.concurrent.locks.Condition*
```java
void await();

// 将该线程放到条件的等待集中

void signalAll();

// 解除该条件的等待集中的所有线程的阻塞状态

void signal();

// 从该条件的等待集中随机地选择一个线程，解除其阻塞状态

boolean await(long time, TimeUnit unit);

// 进入该条件的等待集，直到线程从等待集中移出或等待了指定的时间之后才解除阻塞。如果因为等待时间到了而返回就返回 `false`，否则返回 `true`

void awaitUninteruptibly();

// 进入该条件的等待集，直到线程从等待集移出才解除阻塞。如果线程被中断，该方法不会抛出 `InterruptedException` 异常

```