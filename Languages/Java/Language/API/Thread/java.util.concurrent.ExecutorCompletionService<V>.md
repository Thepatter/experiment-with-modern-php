*java.util.concurrent.ExecutorCompletionService<V>*
```java
ExecutorCompletionService(Executor e);

// 构建一个执行器完成服务来收集给定执行器的结果

Future<V> submit(Callable<V> task);

Future<V> submit(Runnable task, V result);

// 提交一个任务给底层的执行器

Future<V> take();

// 移除下一个已完成的结果，如果没有任何已完成的结果可用则阻塞

Future<V> poll();

Future<V> poll(long time, TimeUnit unit);

// 移除下一个已完成的结果，如果没有任何已完成结果可用则返回 null。第二个方法将等待给定的时间

```