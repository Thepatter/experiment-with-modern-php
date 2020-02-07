*java.lang.Thread*
```java
static void sleep(long millis);

// 休眠给定的毫秒数

// millis	休眠的毫秒数

Thread(Runnable target);

// 构造一个新线程，用于调用给定目标的 `run()` 方法

void start();

// 启动这个线程，将调用 `run()` 方法。这个方法将立即返回，并且新线程将并发运行

void run();

// 用关联 `Runnable` 的 `run` 方法

void interrupt();

// 向线程发送中断请求。线程的中断状态将被设置为 `true` 。如果目前该线程被一个 `sleep` 调用阻塞，那么，`InterruptedException` 异常被抛出

static boolean interrupted();

// 测试当前线程是否被中断。这一调用会产生副作用--它将当前线程的中断状态重置为 false

boolean isInterrupted();

// 测试线程是否被中断。不像静态的中断方法，这一调用不改变线程的中断状态

static Thread currentThread();

// 返回代表当前执行线程的 Thread 对象

void join();

// 等待终止指定的线程

void join(long millis);

// 等待指定的线程死亡或者经过指定的毫秒数

Thread.State getState();

// 得到这一线程的状态：NEW，RUNNABLE，BLOCKED，WAITING，TIMED_WAITING，TERMINATED 之一

void stop();

// 停止该线程。已过时

void suspend();

// 暂停这一线程的执行。已过时

void resume();

// 恢复线程。这一方法仅仅在调用 `suspend()` 之后调用。这一方法已过时

void setPriority(int newPriority);

// 设置线程的优先级。优先级必须在 `Thread.MIN_PRIORITY` 与 `Thread.MAX_PRIORITY` 之间。一般使用 `Thread.NORM_PRIORITY` 优先级

* `static int MIN_PRIORITY`
// 线程的最小优先级。1

* `static int NORM_PRIORITY`
// 线程的默认优先级。5

* `static int MAX_PRIORITY`
// 线程的最高优先级。10

static void yield();

// 导致当前执行线程处于让步状态。如果有其他的可运行线程具有至少与此线程同样高的优先级，那么这些线程接下来会被调度。

void setDaemon(boolean isDaemon);

// 标识该线程为守护线程或用户线程。这一方法必须在线程启动之前调用

static void setDefaultUncaughtExceptionHandler(Thread.UncaughtExctpionHandler handler);

static Thread.UncaughtExceptionHandler getDefaultUncaughtExceptionHandler();

// 设置或获取未捕获异常的默认处理器

void setUncaughtExceptionHandler(Thread.UncaughtExceptionHandler handler);

Thread.UncaughtExceptionHandler getUncaughtExceptionHandler();

// 设置或获取未捕获异常的处理器。如果没有安装处理器，则将线程组对象作为处理器。

ClassLoader getContextClassLoader();

// 获取类加载器，该线程的创建者将其指定为执行该线程时最适合使用的类加载器

void setContextClassLoader(ClassLoader loader);

// 为该线程中的代码设置一个类加载器，以获取要加载的类。如果在启动一个线程时没有显式地设置上下文类加载器，则使用父线程的上下文加载器

```