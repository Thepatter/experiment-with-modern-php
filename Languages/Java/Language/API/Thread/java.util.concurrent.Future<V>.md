*java.util.concurrent.Future<V>*
```java
V get();

V get(long time, TimeUnit unit);

// 获取结果，如果没有结果可用，则阻塞直到真正得到结果超过指定的时间为止。如果不成功，第二个方法会抛出 `TimeoutException` 异常

boolean cancel(boolean mayInterrupt);

// 尝试取消这一任务的运行。如果任务已经开始，并且 `mayInterrupt` 参数值为 true，它就会被中断。如果成功执行了取消操作，返回 true

boolean isCancelled();

// 如果任务在完成前被取消了，则返回 true

boolean isDone();

// 如果任务结束，无论是正常结束，中途取消或发生异常，都返回 true

```