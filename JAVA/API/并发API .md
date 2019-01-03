## 并发相关 API

### java.lang.Thread

* `static void sleep(long millis)`

  休眠给定的毫秒数

  millis	休眠的毫秒数

* `Thread(Runnable target)`

  构造一个新线程，用于调用给定目标的 `run()` 方法

* `void start()`

  启动这个线程，将调用 `run()` 方法。这个方法将立即返回，并且新线程将并发运行

* `void run()`

  用关联 `Runnable` 的 `run` 方法

* `void interrupt()`

  向线程发送中断请求。线程的中断状态将被设置为 `true` 。如果目前该线程被一个 `sleep` 调用阻塞，那么，`InterruptedException` 异常被抛出

* `static boolean interrupted()`

  测试当前线程是否被中断。这一调用会产生副作用--它将当前线程的中断状态重置为 false

* `boolean isInterrupted()`

  测试线程是否被中资。不像静态的中断方法，这一调用不改变线程的中断状态

* `static Thread currentThread()`

  返回代表当前执行线程的 Thread 对象

* `void join()`

  等待终止指定的线程

* `void join(long millis)`

  等待指定的线程死亡或者经过指定的毫秒数

* `Thread.State getState()`

  得到这一线程的状态：NEW，RUNNABLE，BLOCKED，WAITING，TIMED_WAITING 

* `void stop()`

  停止该线程。已过时

* `void suspend()`

  暂停这一线程的执行。已过时

* `void resume()`

  恢复线程。这一方法仅仅在调用 `suspend()` 之后调用。这一方法已过时

* `void setPriority(int newPriority)`

  设置线程的优先级。优先级必须在 `Thread.MIN_PRIORITY` 与 `Thread.MAX_PRIORITY` 之间。一般使用 `Thread.NORM_PRIORITY` 优先级

* `static int MIN_PRIORITY`

  线程的最小优先级。1

* `static int NORM_PRIORITY`

  线程的默认优先级。5

* `static int MAX_PRIORITY`

  线程的最高优先级。10

* `static void yield()`

  导致当前执行线程处于让步状态。如果有其他的可运行线程具有至少与此线程同样高的优先级，那么这些线程接下来会被调度。

* `void setDaemon(boolean isDaemon)`

  标识该线程为守护线程或用户线程。这一方法必须在线程启动之前调用

* `static void setDefaultUncaughtExceptionHandler(Thread.UncaughtExctpionHandler handler)`

* `static Thread.UncaughtExceptionHandler getDefaultUncaughtExceptionHandler()`

  设置或获取未捕获异常的默认处理器

* `void setUncaughtExceptionHandler(Thread.UncaughtExceptionHandler handler)`

* `Thread.UncaughtExceptionHandler getUncaughtExceptionHandler()`

  设置或获取未捕获异常的处理器。如若没有安装处理器，则将线程组对象作为处理器。

### java.lang.Runnable

* `void run()`

  必须覆盖这个方法方法，并在这个方法中提供所要执行的任务指令

### java.lang.Thread.UncaughtExceptionHandler

* `void uncaughtException(Thread t, Throwable e)`

  当一个线程因未捕获异常而终止，按规定要将客户报告记录到日志中

  t			由于未捕获异常而终止的线程

  e			未捕获的异常对象

### java.lang.ThreadGroup

* `void uncaughtException(Thread t, Throwable e)`

  如果有父线程组，调用父线程组的这一方法，或者，如果 `Thread` 类有默认处理器，调用该处理器，否则，输出栈轨迹到标准错误流上（如果  e 是一个 ThreadDeath 对象，栈轨迹是被禁用的。ThreadDeath 对象由 stop 方法产生，而该方法已经过时）

### java.util.concurrent.locks.Lock

* `void lock()`

  获取这个锁；如果锁同时被另一个线程拥有则发生阻塞

* `void unlock()`

  释放这个锁

### java.util.concurrent.locks.ReentrantLock

* `ReetrantLock()`

  构建一个可以被用来保护临界区的可重入锁

* `ReetractLock(boolean fair)`

  构建一个带有公平策略的锁。一个公平锁偏爱等待时间最长的线程。但是，这一公平的保证将大大降低性能。在默认情况下，锁没有被强制为公平的

### java.util.concurrent.locks.Lock

* `Condition newCondition()`

  返回一个与该锁相关的条件对象

### java.util.concurrent.locks.Condition

* `void await()`

  将该线程放到条件的等待集中

* `void signalAll()`

  解除该条件的等待集中的所有线程的阻塞状态

* `void signal()`

  从该条件的等待集中随机地选择一个线程，解除其阻塞状态