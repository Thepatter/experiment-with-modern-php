*java.util.concurrent.Executors*
```java
ExecutorService newCachedThreadPool();

// 返回一个带缓存的线程池，该池在必要的时候创建线程，在线程空闲 60 秒之后终止线程

ExecutorService newFixedThreadPool(int threads);

// 返回一个线程池，该池中的线程数由参数指定

ExecutorService newSingleThreadExecutor();

// 返回一个执行器，它在一个单个的线程中依次执行各个任务

ScheduledExecutorService newScheduledThreadPool(int threads);

// 返回一个线程池，它使用给定的线程数来调度任务

ScheduledExecutorService newSingleThreadScheduledExecutor();

// 返回一个执行器，它在一个单独线程中调度任务

```