### 并发编程

#### 基本的线程机制

##### 多线程多任务

并发编程使我们可以将程序分为多个分离的、独立运行的任务。通过使用多线程机制，这些独立任务中的每一个都将由执行线程来驱动。

###### *Runnable*

线程可以驱动任务，描述任务的方法由 Runnable 接口的 run() 方法提供，该方法不会产生任何内在的线程能力。要实现线程行为，必须将一个任务附着在线程上（*Thread* 类构造器参数）。*Runnable* 是执行工作的独立任务，不返回任何值。

###### *Callable*

如果希望任务在完成时能够返回一个值，需要实现 *Callable* 接口。SE 5 中引入的 *Callable* 是一种具有类型参数的泛型，它的类型参数即 call() 中返回的值，调用 *ExecutorService*.submit() 方法产生 *Future* 对象，它用返回结果的特定类型进行了参数化。可用 isDone() 方法查询  *Future* 对象是否已经完成。当任务完成时，可以调用 get() 方法来获取参数化结果。直接调用 get() 时将阻塞，直到结果准备就绪。get() 支持超时参数。

###### *Thread*

*Thread*.start() 方法（会迅速返回）为该线程执行必须的初始化操作，然后调用 *Runnable*.run() 方法，以便在这个新线程中启动该任务。在 *Thread* 退出 run() 并死亡之前，垃圾回收器无法清除它。一个线程会创建一个单独的执行线程，在对 start() 调用完成之后，它仍旧会继续存在。

执行线程异常不会跨线程传播，必须在执行线程中处理

##### 线程控制

###### 优先级

线程的优先级将该线程的重要性传递了调度器。尽管 CPU 处理现有线程集的的顺序是不确定的，但是调度器将倾向于让优先级最高的线程先执行。（优先级不会导致死锁）优先级较低的线程仅仅是执行的频率较低

在绝大多数时间里，所有线程都应该以默认的优先级运行。试图操纵线程优先级通常是一种错误。JDK 有 10 个优先级，但与大多数操作系统都不能映射的很好，唯一可移植的调整优先级时，只使用 MAX_PRIORITY、NORM_PRIORITY、MIN_PRIORITY 三个级别

```java
// 设置线程优先级，优先级必须在 run 开始部分设置
@Override
public void run() {
		Thread.currentThread().setPriority(Thread.MIN_PRIORITY);
}
```

###### 让步

可以给线程调度机制一个暗示，主动出让 CPU，这个暗示将通过调用 Thread.yield() 方法来作出（只是一个暗示，没有任何机制保证它将会被采纳），不能依赖于 yield() 进行控制

###### 后台线程

daemon 线程，是指在程序运行的时候在后台提供一种通用服务的线程，并且这种线程并不属于程序中不可或缺的部分。因此，当所有的非后台线程结束时，程序也就终止了，同时会杀死进程中的所有后台线程。只要有任何非后台线程还在运行，程序就不会终止。执行 main() 的就是一个非后台线程。后台线程创建的线程将自动成为后台线程

必须在线程启动之前调用 setDaemon() 方法，才能把它设置为后台线程。

```java
Thread thread = new Thread(new Runnable());
thread.setDaemon(true);
thread.start();
```

后台线程在不执行 finally 子句的情况下就会终止其 run() 方法（当最后一个非后台线程终止时，后台线程会『突然』终止。因此一旦 main() 退出，jvm 就会立即关闭所有的后台进程，而不会有任何希望出现的确认形式。非后台的 Executor 通常是一种更好的方式，Executor 控制的所有任务可以同时被关闭）

###### 加入一个线程

一个线程可以在其他线程之上调用 join() 方法，其效果是等待一段时间直到第二个线程结束才继续执行。如果某个线程在另一个线程 t 上调用 t.join()，此线程将被挂起，直到目标线程 t 结束才恢复（即 t.isAlive == false）

也可以在调用join（）时带上一个超时参数（单位可以是毫秒，或者毫秒和纳秒），这样如果目标线程在这段时间到期时还没有结束的话，join（）方法总能返回

对join（）方法的调用可以被中断，做法是在调用线程上调用interrupt（）方法，这时需要用到try-catch子句

###### 线程组

线程组持有一个线程集合，『最好把线程组看成一次不成功的尝试，你只要忽略它就好了』

###### 捕获异常

由于线程的本质特性，使得不能捕获从线程中逃逸的异常，一旦异常逃出任务的 run() 方法，它就会向外传播到控制台，除非采取特殊的步骤捕获这种错误的异常。在 SE 5 之前，可以使用线程组来捕获这些异常，但 SE 5 开始，就可以用 *Executor* 来解决这个问题。

修改 *Executor* 产生线程的方式。Thread.UncaughtExceptionHandler 是 SE 5 中的新接口，允许在每个 Thread 对象上都附着一个异常处理器。Thread.UncaughtExceptionHandler.uncaughtException() 会在线程因未捕获的异常而临近死亡时被调用。为了使用它，创建一个新类型的 *ThreadFactory*，它将在每个新创建的 Thread 对象上附着一个 Thread.UncaughtExceptionHandler，将该工厂传递给 Executors 创建新的 ExecutorService 方法

###### 线程本地存储

防止任务在共享资源上产生冲突除了使用同步之外，可以使用线程本地存储，可以为使用相同变量的每个不同的线程都创建不同的存储。

*ThreadLocal* 对象通常当作静态域存储，在创建 *ThreadLocal* 时，只能通过 get() 和 set() 方法来访问该对象的内容，其中 get() 方法将返回与其线程相关联的对象的副本，set() 会将参数插入到为其线程存储的对象中，并返回存储中原有的对象。ThreadLocal 保证不会出现竞争条件

##### 线程池

###### Executor

SE 5 的 *java.util.concurrent* 包中的执行器（*Executor*）管理 *Thread* 对象，在客户端和任务执行之间提供了一个间接层，这个中介对象将执行任务。*Executor* 允许管理异步任务的执行，而无须显式管理线程的生命周期。通过编写定制的 *ThreadFactory* 可以定制由 *Executor* 创建的线程的属性（后台、优先级、名称）

对 shutdown() 方法的调用可以防止新任务被提交给这个 *Executor*，当前线程将继续运行在 shutdown() 被调用之前提交的所有任务。这个程序将在 Executor 中的所有任务完成之后尽快退出。在任何线程池中，现有线程在可能的情况下，都会被自动复用，具有服务生命周期的 *ExecutorService*，由 *Executor* 静态方法创建

*   *CachedThreadPool*

    *CachedThreadPool* 在程序执行过程中通常会创建与所需数量相同的线程，然后在它回收旧线程时停止创建新线程，是合理的 *Executor* 的首选

*   *FixedThreadPool*

    一次性预先执行代价高昂的线程分配，因而也就可以限制线程的数量了。这可以节省时间，因为你不用为每个任务都固定地付出创建线程的开销。在事件驱动的系统中，需要线程的事件处理器，通过直接从池中获取线程，也可以如你所愿地尽快得到服务。你不会滥用可获得的资源，因为 FixedThreadPool 使用的 Thread 对象的数量是有界的

*   *SingleThreadExecutor*

    使用单个线程完成任务，序列化任务。如果向 SingleThreadExecutor 提交了多个任务，那么这些任务将排队，每个任务都会在下一个任务开始之前运行结束，所有的任务将使用相同的线程

###### 手动创建线程池

线程池不允许使用 Executors 去创建，而是通过 ThreadPoolExecutor 的方式，这样的处理方式更加明确线程池的运行规则，规避资源耗尽的风险。 说明：Executors 返回的线程池对象的弊端如下：

1）FixedThreadPool 和 SingleThreadPool:
  允许的请求队列长度为 Integer.MAX_VALUE，可能会堆积大量的请求，从而导致 OOM。
2）CachedThreadPool:
  允许的创建线程数量为 Integer.MAX_VALUE，可能会创建大量的线程，从而导致OOM。

```java
public class UserThreadFactory implements ThreadFactory {
    private final String namePrefix;
    private final AtomicInteger nextId = new AtomicInteger(1);
    UserThreadFactory(String whatFeatureOfGroup) {
        namePrefix = "From UserThreadFactory's " + whatFeatureOfGroup + "-Worker-";
    }
    @Override
    public Thread newThread(Runnable task) {
        String name = namePrefix + nextId.getAndIncrement();
        return new Thread(null, task, name, 0, false);
    }
    public static void main(String[] args) {
        ExecutorService executor = new ThreadPoolExecutor(6, 12, 60, TimeUnit.SECONDS,
                        new LinkedTransferQueue<>(), new UserThreadFactory("mine"));
        for (int i = 0; i < 5; i++) {
            executor.execute(() ->
                    System.out.println("THREAD NAME :" + Thread.currentThread().getName() + " ID: " + Thread.currentThread().getId()));
        }
        executor.shutdown();
    }
}
```

```xml
    <bean id="userThreadPool"
        class="org.springframework.scheduling.concurrent.ThreadPoolTaskExecutor">
        <property name="corePoolSize" value="10" />
        <property name="maxPoolSize" value="100" />
        <property name="queueCapacity" value="2000" />
    	<property name="threadFactory" value= threadFactory />
        <property name="rejectedExecutionHandler">
            <ref local="rejectedExecutionHandler" />
        </property>
    </bean>
    //in code
    userThreadPool.execute(thread);
```

#### 同步相关

##### 原子类

SE 5 中引入了原子性变量类，它提供了原子性条件更新操作

```java
boolean compareAndSet(expectedValue, updateValue);
```

这些类是在机器级别上的原子性，主要涉及性能调优时

SE 5 并发类库中添加了一个特性，在 *ReentrantLock* 上阻塞的任务具备可以被中断的能力

###### 检查中断

在线程上调用 interrupt() 时，中断发生的唯一时刻是在任务要进入到阻塞操作中，或者已经在阻塞操作内部时。如果根据程序运行的环境，已经编写了可能会产生这种阻塞的代码，如果只能通过在阻塞调用上抛出异常来退出，那么就无法总是可以离开 run() 循环。如果调用 interrupt() 以停止任务，那么在 run() 循环碰巧没有产生任何阻塞调用的情况下，任务需要由中断状态来表示的。其状态可以通过调用 interrupt() 来设置。可以通过调用 interrupted() 来检查中断状态，这不仅可以获得 interrupt() 是否被调用过，而且还可以清除中断状态。清除中断状态可以确保并发结构不会就某个任务被中断这个问题通知你两次，可以经由单一的 *InterruptedException* 或单一的成功的 Thread.interrupted() 测试来得到这种通知

在 shutdownNow() 被调用时，*PipedReader* 是可以中断的

#### 线程之间的协作

当任务协作时，关键问题是这些任务之间的握手，为了实现这种握手，使用互斥，在这种情况下，互斥能够确保只有一个任务可以响应某个信号，这样就可以根除任何可能的竞争条件。在互斥之上，为任务添加了一种途径，可以将其自身挂起，直至某些外部条件发生变化。

握手可以通过 Object 的方法 wait() 和 notify() 来安全地实现。SE 5 的并发类库还提供了具有 await() 和 signal() 方法的 Condition 对象。

##### wait 与 notifyAll

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

#### concurrent 库

SE 5 的 java.util.concurrent 引入了大量设计用来解决并发问题的新类

##### CountDownLatch

被用来同步一个或多个任务，强制它们等待由其他任务执行的一组操作完成。可以向 *CountDownLatch* 对象设置一个初始计数值，任何在这个对象上调用 wait() 的方法都将阻塞，直至这个数值到达 0。其他任务在结束其工作时，可以在该对象调用 countDown() 来减少这个计数值。*CountDownLatch* 被设计为只触发一次，计数值不能被重置。*CyclicBarrier* 支持计数器重置。

调用 countDown() 的任务在产生这个调用时并没有被阻塞，只有对 await() 的调用会被阻塞，直至计数值到达 0

*CountDownLatch* 的典型用法是将一个程序分为 n 个互相独立的可解决任务，并创建值为 0 的 *CountDownLatch* 当每个任务完成时，都会在这个锁存器上调用 countDown()。等待问题被解决的任务在这个锁存器上调用 await()，将它们自己挡住，直至锁存器计数结束

##### CyclicDBarrier

类似『线程屏障』，它使得所有的并行任务都将处于栅栏处队列，因此可以一致地向前移动。非常像 *CountDownLatch*，只是 *CountDownLatch* 是只触发一次的事件，而 *CyclicBarrier* 可以多次重用

可以向 *CyclicBarrier* 提供一个『栅栏动作』，它是一个 *Runnable*，当计数值到达 0 时自动执行。

##### ScheduledExecutor

*ScheduledThreadPoolExecutor* 通过使用 schedule() （运行一次）或 scheduleAtFixedRate()（每隔规则的时间重复执行任务），可以将 Runnable 对象设置为将来的某个时刻执行。

##### Semaphore

concurrent.locks 或 synchronized 对象锁在任何时刻都只允许一个任务访问一项资源，而『计数信号量』允许 n 个任务同时访问这个资源。

##### CopyOnWriteArrayList

写入将导致创建整个底层数组的副本，而源数组将保留在原地，使得复制的数组在被修改时，读取操作可以安全的执行。当修改完成时，一个原子性的操作将把新的数组换入，使得新的读取操作可以看到这个修改。

<u>*CopyOnWriteArrayList* 可以使多个迭代器同时遍历和修改这个列表时，不会抛出 *ConcreentModificationException*</u>

*CopyOnWriteArraySet* 将使用 *CopyOnWriteArrayList* 来实现。

