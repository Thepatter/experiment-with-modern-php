### 并发编程

#### 基本的线程机制

##### 任务

并发编程使我们可以将程序分为多个分离的、独立运行的任务。通过使用多线程机制，这些独立任务中的每一个都将由执行线程来驱动。

###### *Runnable*

线程可以驱动任务，描述任务的方法由 Runnable 接口的 run() 方法提供，该方法不会产生任何内在的线程能力。要实现线程行为，必须将一个任务附着在线程上（*Thread* 类构造器参数）。*Runnable* 是执行工作的独立任务，不返回任何值。

Runnable 中抛出的异常在会在对应的执行线程中抛出，输出到标准错误，需要在主线程中捕获 run() 方法异常时：

````java
public static Runnable runnable() {
    return () -> {
        if (System.currentTimeMillis() % 9 == 0) {
            System.out.println(7);
        }
        throw new NullPointerException("throw null exception in main thread");
    };
}
public static void runThrowInMainThread() {
    // 设置所有线程的默认异常处理器
    Thread.setDefaultUncaughtExceptionHandler((t, e) -> {
        if (e instanceof NullPointerException) {
            System.out.println("catch null exception");
        }
    });
    new Thread(ThreadThrowDemo.runnable()).start();
}
public static void runThrowInMainByPool() {
    ExecutorService executorService = Executors.newCachedThreadPool(r -> {
        Thread thread = new Thread(r);
        thread.setUncaughtExceptionHandler((t, e) -> {
            if (e instanceof NullPointerException) {
                System.out.println("catch null exception");
            }
        });
        return thread;
    });
    executorService.execute(ThreadThrowDemo.runnable());
}
````

###### *Callable*

如果希望任务在完成时能够返回一个值，需要实现 *Callable* 接口。SE 5 中引入的 *Callable* 是一种具有类型参数的泛型，它的类型参数即 call() 中返回的值，调用 *ExecutorService*.submit() 方法产生 *Future* 对象，它用返回结果的特定类型进行了参数化。可用 isDone() 方法查询  *Future* 对象是否已经完成。当任务完成时，可以调用 get() 方法来获取参数化结果。直接调用 get() 时将阻塞，直到结果准备就绪。get() 支持超时参数。

call() 方法可以抛出异常到主线程。

###### *Thread*

*Thread*.start() 方法（会迅速返回）为该线程执行必须的初始化操作，然后调用 *Runnable*.run() 方法，以便在这个新线程中启动该任务。在 *Thread* 退出 run() 并死亡之前，垃圾回收器无法清除它。一个线程会创建一个单独的执行线程，在对 start() 调用完成之后，它仍旧会继续存在。

##### 线程属性

###### 状态

* NEW

    新建状态，线程被创建且未启动的状态，只会短暂处于这种状态，此时已经分配了必须的系统资源并进行了初始化

* RUNNABLE

    就绪状态，调用 start() 之后，运行之前的状态。线程的 start() 不能被多次调用，否则会抛出 *IllegalStateException*

* RUNNING

    运行状态，run() 正在执行时线程的状态。此时线程占有 CPU

* BLOCKED

    阻塞，以下状况可能导致阻塞

    1.  同步阻塞：等待锁或 IO 完成
    2.  主动阻塞：线程执行 sleep()、join() 方法
    3.  等待阻塞：自身任务执行完成，调用 wait() 等待，或等待 *Condition* 信号

* DEAD

    终止，run() 执行结束，或异常退出的状态，此状态不可逆转

###### 优先级

线程的优先级传给调度器后，调度器将倾向于让优先级最高的线程先执行。（优先级不会导致死锁）优先级较低的线程仅仅是执行的频率较低

在绝大多数时间里，所有线程都应该以默认的优先级运行。试图操纵线程优先级通常是一种错误。JDK 有 10 个优先级，但与大多数操作系统都不能映射的很好，唯一可移植的调整优先级时，只使用 *Thread*.MAX_PRIORITY、*Thread*.NORM_PRIORITY、*Thread*.MIN_PRIORITY 三个级别

```java
// 设置线程优先级，优先级必须在 run 开始部分设置
@Override
public void run() {
	Thread.currentThread().setPriority(Thread.MIN_PRIORITY);
}
```

Oracle 为 Linux 提供的 Java 虚拟机中，线程的优先级被忽略，所有的线程具有相同的优先级

###### 后台线程

daemon 线程，是指在程序运行的时候在后台提供一种通用服务的线程，并且这种线程并不属于程序中不可或缺的部分。因此，当所有的非后台线程结束时，程序也就终止了，同时会杀死进程中的所有后台线程。只要有任何非后台线程还在运行，程序就不会终止。执行 main() 的就是一个非后台线程。后台线程创建的线程将自动成为后台线程

```java
// 必须在线程启动之前调用 setDaemon() 方法，才能把它设置为后台线程。
Thread thread = new Thread(new Runnable());
thread.setDaemon(true);
thread.start();
```

后台线程在不执行 finally 子句的情况下就会终止其 run() 方法（当最后一个非后台线程终止时，后台线程会『突然』终止。因此一旦 main() 退出，jvm 就会立即关闭所有的后台进程，而不会有任何希望出现的确认形式）

###### 线程中断

没有可以强制线程终止的方法。*Thread*.interrupt() 可以用来请求终止线程（会设置线程的中断状态）如果线程被阻塞（sleep），或试图执行一个阻塞操作。设置这个线程的中断状态将抛出 *InterruptedException*，当抛出该异常或该任务调用 *Thread*.interrupted() 时，中断状态将被复位

*   中断一个线程，须持有该线程的 *Thread* 对象。
*   中断线程池中的所有线程，调用在 *Executor*.shutdownNow()，它将发送一个 interrupt() 调用给它启动的所有线程
*   中断线程池中单个线程，使用 submit 启动任务，在返回的 *Future* 上调用 `cacel(true)` 来中断

###### 加入一个线程

一个线程可以在其他线程之上调用 join() 方法，其效果是等待一段时间直到第二个线程结束才继续执行。如果某个线程在另一个线程 t 上调用 t.join()，此线程将被挂起，直到目标线程 t 结束才恢复（即 t.isAlive == false）

也可以在调用 join() 时带上一个超时参数（单位可以是毫秒，或者毫秒和纳秒），这样如果目标线程在这段时间到期时还没有结束的话，join() 方法总能返回

对 join() 方法的调用可以被中断，做法是在调用线程上调用 interrupt（）方法，这时需要用到 try-catch子句

###### 线程组

线程组持有一个线程集合，『最好把线程组看成一次不成功的尝试，你只要忽略它就好了』

###### *ThreadLocal*

防止任务在共享资源上产生冲突除了使用同步之外，可以使用线程本地存储，可以为使用相同变量的每个不同的线程都创建不同的存储。是每个线程单独持有的，常用作静态域。用于传递线程内变量。

在创建 *ThreadLocal* 时，只能通过 get() 和 set() 方法来访问该对象的内容，其中 get() 方法将返回与其线程相关联的对象的副本，set() 会将参数插入到为其线程存储的对象中，并返回存储中原有的对象。ThreadLocal 保证不会出现竞争条件。*ThreadLocal* 无法解决共享对象的更新问题，如果使用某个引用来操作共享对象时，依然需要进行同步线程输出

在线程池中使用 *ThreadLocal* 会产生：

*   脏数据

    线程复用会产生脏数据，线程池会重用 *Thread* 对象，与 *Thread* 绑定的类静态属性 *ThreadLocal* 变量也会被重用。如果在实现的线程不显式调用 remove() 清理线程相关的 *ThreadLocal* 信息，如果下一个线程不调用 set 设置初始值，就可能 get 到重用的线程信息。包括 *ThreadLocal* 所关联的线程对象的 value 值
    
*   内存泄漏

    *ThreadLocal* 对象失去引用后，无法通过弱引用机制来回收 entry 的 value，必须调用 remove 方法清理

##### 线程池

线程池作用包括：

*   利用线程池管理并复用线程、控制最大并发数
*   实现任务线程队列缓存策略和拒绝机制
*   实现定时执行、周期执行
*   隔离线程环境

###### *Executor*

SE 5 的 *java.util.concurrent* 包中的执行器（*Executor*）管理 *Thread* 对象，在客户端和任务执行之间提供了一个间接层，这个中介对象将执行任务。*Executor* 允许管理异步任务的执行，而无须显式管理线程的生命周期。通过编写定制的 *ThreadFactory* 可以定制由 *Executor* 创建的线程的属性（后台、优先级、名称）

对 shutdown() 方法的调用可以防止新任务被提交给这个 *Executor*，当前线程将继续运行在 shutdown() 被调用之前提交的所有任务。这个程序将在 Executor 中的所有任务完成之后尽快退出。

###### *ExecutorService*

继承 *Executor*，定义了管理线程任务方法。通过 *Executors* 静态工厂方法可以创建三个线程池的包装对象：*ForkJoinPool*、*ThreadPoolExecutor*、*ScheduledThreadPoolExecutor*

*   *Executors*.newWorkStrealingPool()

    JDK 8 引入，返回一个默认 CPU 核数的 *ForkJoinPool*

*   *Executors*.CachedThreadPool()

    返回与所需要任务数线程 *ThreadPoolExecutor*。最大线程数可至 *Integer*.MAX_VALUE，可能会创建大量线程，导致 OOM，keepAliveTime 为 60，工作线程处于空闲状态，则回收，如果任务数增加，再次创建出新线程

*   *Executors*.FixedThreadPool()

    返回固定线程数 *ThreadPoolExecutor*，输入的参数即时固定线程数，不存在空闲线程，keepAliveTime 为 0。允许请求队列为 *Integer*.MAX_VALUE，可能会堆积大量请求，导致 OOM

*   *Executors*.SingleThreadExecutor()

    返回单线程的 *ThreadPoolExecutor*，单线程串行执行所有任务，保证任务提交顺序执行。允许请求队列为 *Integer.MAX_VALUE* 可能会堆积大量请求，导致 OOM

###### *ThreadPoolExecutor*

手动创建线程池，通过创建 *ThreadPoolExecutor* 对象来实现。最多支持 7 个构成参数来定义线程属性：

*   corePoolSize

    常驻核心线程数，如果等于 0，则任务执行完之后，没有任何请求进行时销毁线程池的线程；如果大于 0，即使本地任务执行完毕，核心线程也不会被销毁。

*   maximumPoolSize

    线程池能够容纳同时执行的最大线程数，必须大于或等于 1

*   keepAliveTime

    线程池的线程空闲时间，当空闲时间达到此值时，线程会被销毁，直到剩下 corePoolSize 数线程为止。默认当线程池的线程数大于 corePoolSize，该参数才起作用。当 *ThreadPoolExecutor*.allowCoreThreadTimeOut(true) 时，核心线程超时后也会被回收

*   unit

    keepAliveTime 的时间单位，枚举类 *TimeUnit*

*   workQueue

    缓存队列，当请求的线程大于 maximumPoolSize 时，线程进入 *BlockingQueue* 阻塞队列。

*   threadFactory

    线程工厂实例，生成线程实例时可以指定线程名前缀

*   handler

    执行拒绝策略的对象。当超过 workQueue 任务缓冲区上限时，可以通过该策略处理请求。会将任务和线程池对象传入该策略对象

##### 线程同步

在多线程对同一变量进行写操作时，如果操作没有原子性（CAS，compare and swap 是原子操作），可能产生脏数据。

###### volatile

volatile 关键字为实例域的同步访问提供了免锁机制，voliatile 域不提供原子性

volatile 确保了应用的可视性。如果将一个域声明为 volatile，只要对这个域产生了写操作，所有的读操作都可以看到这个修改。即时使用了本地缓存。volatile 域会立即被写入到主存中。非 volatile 域上的原子操作不必刷新到主存中。

如果多个任务在同时访问某个域，那么这个域就应该是 volatile 的，否则这个域只能由同步来访问，同步也会导致向主存中刷新，如果一个域完全由 synchronized 方法或语句来保护，则不必将其设置为 volatile

一个任务所作的任何写入操作对这个任务来说都是可视的，如果只需要在这个任务可视，则不需要将其设置为 volatile

当一个域的值依赖于它之前的值时，volatile 就无法工作，如果某个域的值受到其他域的值的限制，volatile 也无法工作。使用 volatile 而不是 synchronized 的唯一安全的情况是类中只有一个可变的域。

###### 信号量同步

通过传递同步信号量来协调线程执行顺序

###### synchorized

使用对象内部锁进行同步，支持代码块（临界区）级别同步。锁特性由 JVM 实现，底层通过 monitor（即对象头字段）来实现 synchronized，JVM 根据当前缓解找到对应对象得 monitor，根据 monitor 状态进行加、解锁判断。线程再进入同步方法或代码块时，会获取该方法或代码块所属对象得 monitor，进行加锁判断。如果成功加锁就成为该 monitor 得唯一持有者。monitor 在被释放前，不能再被其他线程获取。

JDK 6 之后，synchronized 提供三种锁得实现：偏向锁、轻量级锁、重量级锁和自动升降级机制。

```java
synchronized(this) {
	// todo
}
```

###### *Lock*

*Lock* 对象必须被显式地创建、锁定和释放。相比 synchronized 关键字，可以在 finally 进行清理工作，只有在解决特殊问题时，才使用显式 *Lock*

```java
private Lock lock = new ReentrantLock();
public int next() {
    lock.lock();
    try {
        // tudo
        return ... // return 必须在 try 子句中
    } finally {
        lock.unlock();
    }
}
```

##### 线程之间的协作

当任务协作时，关键问题是这些任务之间的握手，为了实现这种握手，使用互斥，在这种情况下，互斥能够确保只有一个任务可以响应某个信号，这样就可以根除任何可能的竞争条件。在互斥之上，为任务添加了一种途径，可以将其自身挂起，直至某些外部条件发生变化。

握手可以通过 Object 的方法 wait() 和 notify() 来安全地实现。SE 5 的并发类库还提供了具有 await() 和 signal() 方法的 Condition 对象。

###### wait 与 notifyAll

wait() 使线程等待某个条件发生变化，而改变这个条件超出了当前方法的控制能力，通常这种条件将由另一个任务来改变。wait() 会在等待外部时间产生变化的时候将任务挂起，并且只有在 notify() 或 notifyAll() 发生时，这个任务才会被唤醒并去检查所产生的变化。

调用 sleep() 和 yield() 时锁并没有被释放，<u>当一个任务在方法里遇到了对 wait() 的调用的时候，线程的执行被挂起，对象上的锁被释放</u>

wait()、notify()、notifyAll() 有一个比较特殊的方面，这些是基类 Object 的一部分，而不是属于 Thread 的一部分。因为这些方法操作的锁也是所有对象的一部分。可以把 wait() 放进任何同步控制方法中，而不用考虑这个类是继承自 Thread 还是实现了 Runnable 接口。实际上只能在同步控制方法或同步控制块里调用 wait()，notify()，notifyAll()（调用这些方法前必须拥有对象的锁）如果在非同步孔子方法里调用这些方法，程序能通过编译，但运行时，将得到 *IllegalMonitorStateException* 异常。

###### notify() 和 notifyAll()

使用 notify() 而不是 notifyAll() 是一种优化，使用 notify() 时，在众多等待同一个锁的任务中只有一个会被唤醒。如果使用 notify()，就必须保证被唤醒的是恰当的任务，所有任务必须等待相同的条件，如果有多个任务在等待不同的条件，那么就不会知道是否唤醒了恰当的任务。

###### 显式的 Lock 和 Condition 对象

在 SE 5 的 java.util.concurrent 类库中还有额外的显式 Lock 和 Condition 对象。使用互斥并允许任务挂起的基本类 *Condition*，可以在 *Condition* 上调用 await() 来挂起一个任务，当外部条件发生变化，意味着某个任务应该继续执行时，可以通过调用 signal() 来通知这个任务，从而唤醒一个任务，或者调用 signalAll() 来唤醒所有在这个 *Condition* 上被其自身挂起的任务（与 notifyAll() 相比，signalAll() 是更安全的方式）

##### 死锁

当以下四个条件同时满足时，就会发生死锁：

1. 互斥条件
2. 至少有一个任务它必须持有一个资源且正在等待获取一个当前被别的任务持有的资源
3. 资源不能被任务抢占，任务必须把资源释放当作普通事件
4. 必须有循环等待，这时，一个任务等待其他任务所持有的资源，后者又在等待另一个任务所持有的资源，这样一直下去，直到有一个任务在等待第一个任务所持有的资源，使得大家都被锁住

#### 并发工具包

SE 5 的 java.util.concurrent 引入了大量设计用来解决并发问题的新类

##### 其他工具

###### *AbstractQueuedSynchronizer*

抽象类，并发包实现同步的基础工具。其定义了私有的 valatile state 变量作为共享资源。如果线程获取资源失败，则进入同步 FIFO 队列中等待；如果成功获取资源就执行临界区代码。执行完释放资源时，会通知同步队列中的等待线程来获取资源后出队并执行

内置自旋锁实现的同步队列，封装入队和出队的操作，提供独占、共享、中断。

##### 锁工具

###### *ReentrantLock*

可重入锁，实现了 *Lock* 接口，依赖于内部类 *Sync*（继承了 *AbstractQueuedSynchronizer*），定义 state 为 0 时可以获取资源并置为 1，若已获得资源，state 不断加 1，在释放资源时 state 减 1，直至为 0

###### *CountDownLatch*

初始定义了资源总量 state = count，countDown() 不断将 state 减少 1，当 state = 0 时才能获得锁，释放完后 state 就一直为 0，所有线程调用 await() 都不会等待。*CountDownLatch* 是一次性的，用完后如果再想用只能重新创建

被用来同步一个或多个任务，强制它们等待由其他任务执行的一组操作完成。可以向 *CountDownLatch* 对象设置一个初始计数值，任何在这个对象上调用 wait() 的方法都将阻塞，直至这个数值到达 0。其他任务在结束其工作时，可以在该对象调用 countDown() 来减少这个计数值。*CountDownLatch* 被设计为只触发一次，计数值不能被重置。*CyclicBarrier* 支持计数器重置。

调用 countDown() 的任务在产生这个调用时并没有被阻塞，只有对 await() 的调用会被阻塞，直至计数值到达 0

*CountDownLatch* 的典型用法是将一个程序分为 n 个互相独立的可解决任务，并创建值为 0 的 *CountDownLatch* 当每个任务完成时，都会在这个锁存器上调用 countDown()。等待问题被解决的任务在这个锁存器上调用 await()，将它们自己挡住，直至锁存器计数结束

###### *CyclicDBarrier*

类似『线程屏障』，它使得所有的并行任务都将处于栅栏处队列，因此可以一致地向前移动。非常像 *CountDownLatch*，只是 *CountDownLatch* 是只触发一次的事件，而 *CyclicBarrier* 可以多次重用

可以向 *CyclicBarrier* 提供一个『栅栏动作』，它是一个 *Runnable*，当计数值到达 0 时自动执行。

###### *Semaphore*

定义了资源总量 state = permits，当 state > 0 时就能获得锁，并将 state 减 1，当 state = 0 时只能等待其他线程释放锁，当释放锁时 state 加 1，其他等待线程又能获得这个锁。当 permits 定义为 1 时，就是互斥锁，当 permits > 1 时就是共享锁

concurrent.locks 或 synchronized 对象锁在任何时刻都只允许一个任务访问一项资源，而『计数信号量』允许 n 个任务同时访问这个资源。

ScheduledExecutor

*ScheduledThreadPoolExecutor* 通过使用 schedule() （运行一次）或 scheduleAtFixedRate()（每隔规则的时间重复执行任务），可以将 Runnable 对象设置为将来的某个时刻执行。

###### *StampedLock*

JDK8 新增，改进了读写锁 *ReentrantReadWriteLock*

##### 并发数据结构

###### *CopyOnWriteArrayList*

写入将导致创建整个底层数组的副本，而源数组将保留在原地，使得复制的数组在被修改时，读取操作可以安全的执行。当修改完成时，一个原子性的操作将把新的数组换入，使得新的读取操作可以看到这个修改。

<u>*CopyOnWriteArrayList* 可以使多个迭代器同时遍历和修改这个列表时，不会抛出 *ConcreentModificationException*</u>

*CopyOnWriteArraySet* 将使用 *CopyOnWriteArrayList* 来实现。

##### 线程安全原子类

SE 5 中引入了原子性变量类，它提供了原子性条件更新操作

```java
boolean compareAndSet(expectedValue, updateValue);
```

这些类是在机器级别上的原子性，主要涉及性能调优时

SE 5 并发类库中添加了一个特性，在 *ReentrantLock* 上阻塞的任务具备可以被中断的能力