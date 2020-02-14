### Concurrency

#### Thread

##### java 中线程的定义

java 内部支持多线程，在一个程序中允许同时允许多个任务。可以在程序中创建附加的线程以执行并发任务，在java 中，每个任务都是 Runnable 接口的实例，一个任务类必须实现 Runnable 接口，任务必须从线程运行

```java
public class TaskClass implements Runnable {
    @Override
    public function run(); // 线程执行方法
}
// 构建任务
TaskClass task = new TaskClass();
// 运行任务
Thread thread = new Thread(task);
thread.start();
```

任务中的 run 方法指明如何完成这个任务，java 虚拟机会自动调用该方法，无需特意调用它，直接调用 run 只是在同一个线程中执行该方法，而没有新线程启动。Thread 类包含为任务而创建的线程的构造方法，以及控制线程的方法

##### 中断线程

当线程的 run() 方法执行方法体中最后一条语句后，并经由执行 return 语句返回时，或者出现了在方法中没有捕获的异常时，线程将终止。在 java 的早期版本中，还有一个 stop() 方法，其他线程可以调用它终止线程。现在已经被弃用了。

没有可以强制线程终止的方法。interrupt() 方法可以用来请求终止线程，当对一个线程调用 interrupt 方法时，线程的中断状态将被置位。这是每一个线程都具有的 boolean 标志。每个线程都应该不时检查这个标志，以判断线程是否被中断，如果线程被阻塞，就无法检测中断状态。当在一个被阻塞的线程（调用 sleep 或 wait）上调用 interrupt 方法，阻塞调用将会被 InterruptedException 异常中断。

##### 线程状态

线程有 6 类状态

* New (新创建)

* Runnable (可运行)

* Blocked (被阻塞)

* Waiting  (等待)

* Timed waiting (记时等待）

* Terminated (被终止)

  ​				*线程状态*

![](../Images/线程状态.png)

###### 新创建线程

当用 new 操作符创建一个新线程时，该线程还没有开始运行。这意味着它状态是 new 。当一个线程处于新创建状态时，程序还没有开始运行线程中的代码。在线程运行之前还有一些基础工作要做

###### 可运行线程

一旦调用 start 方法，线程处于 runnable 状态。一个可运行的线程可能正在运行也可能没有运行，这取决于操作系统给线程提供运行的时间。（java 规范中，一个正在运行中的线程仍然处于可运行状态）

一旦一个线程开始运行，它不必始终保持运行。事实上，运行中的线程被中断，目的是为了让其他线程获得远行机会。线程调度的细节依赖于操作系统提供的服务。抢占式调度系统给每一个可运行线程一个时间片来执行任务。当时间片用完，操作系统剥夺该线程的运行权，并给另一个线程运行机会。当选择下一个线程时，操作系统考虑线程的优先级别

现在所有的桌面以及服务器操作系统都使用抢占式调度。但是，像手机这样的小型设备可能使用协作式调度。在这样的设备中，一个线程只有在调用 yield 方法，或者被阻塞或等待时，线程才失去控制权

在具有多个处理器的机器上，每一个处理器运行一个线程，可以有多个线程并行运行。当然，如果线程的数目多于处理器的数目，调度器依然采用时间片机制

在任何给定时刻，一个可运行的线程可能正在运行也可能没有运行

###### 被阻塞线程和等待线程

当线程处于被阻塞或等待状态时，它暂时不活动。它不运行任何代码且消耗最少的资源。直到线程调度器重新激活它。细节取决于它是怎样达到非活动状态的

* 当一个线程试图获取一个内部的对象锁（而不是 java.util.concurrent 库中的锁），而该锁被其他线程持有，则该线程进入阻塞状态。当所有其他线程释放该锁，并且线程调度器允许本线程持有它的时候，该线程将变成非阻塞状态
* 当线程等待另一个线程通知调度器一个条件时，它自己进入等待状态。在调用 Object.wait 方法或 Thread.join 方法，或者是等待 java.util.comcurrent 库中的 Lock 或 Condition 时，就会出现这种情况。实际上，被阻塞状态与等待状态是有很大不同的
* 有几个方法有一个超时参数。调用它们导致线程进入计时等待状态。这一状态将一直保持到超时期满或者接收到适当的通知。带有超时参数的方法有 Thread.sleep 、Object.wait、Thread.join 、Lock.tryLock 以及Condition.await 的计时版

###### 被终止的线程

线程因如下两个原因之一被终止

* 因为 run 方法正常退出而终止
* 因为一个没有捕获的异常终止了 run 方法而意外终止

可以调用线程的 stop() 方法杀死一个线程。该方法抛出 *ThreadDeath* 错误对象，由此杀死线程，stop() 方法已过时，不要在代码中调用这个方法

##### 线程属性

###### 线程优先级

在 java 中，每一个线程有一个优先级。默认情况下，一个线程继承它的父线程的优先级。可以用 setPriority() 方法提高或降低任何一个线程的优先级。可以将优先级设置为 MIN_PRIORITY ~ MAX_PRIORITY（1 ～ 10）之间的任何值

每当线程调度器有机会选择新线程时，它首先选择具有较高优先级的线程。线程优先级是高度依赖于系统的。当虚拟机依赖于宿主机平台的线程实现机制时，java 线程的优先级被映射到宿主机平台的优先级上，优先级个数也许会更多或更少

Oracle 为 Linux 提供的 Java 虚拟机中，线程的优先级被忽略，所有的线程具有相同的优先级，不要将程序构建为功能的正确性依赖于优先级

###### 守护线程

setDaemon() 将线程转换为守护线程。守护线程的唯一用途是为其他线程提供服务。守护线程应该永远不去访问固有资源，它会在任何时候甚至在一个操作的中间发生中断

###### 未捕获异常处理器

线程的 run() 方法不能抛出任何受查异常，非受查异常会导致线程终止。在这种情况下，线程就死亡了。但是，不需要任何 catch 子句来处理可以被传播的异常。相反，就在线程死亡之前，异常被传递到一个用于未捕获异常的处理器。

该处理器必须属于一个实现 Thread.UncaughtExceptionHandler 接口的类。这个接口只有一个方法 uncaughtExcepion(Thread t, Throwable e) 可以用 setUncaughtExceptionHandler 方法为任何线程安装一个处理器。也可以用 Thread 类的静态方法 setDefaultUncaughtExceptionHandler 为所有线程安装一个默认的处理器。替换处理器可以使用日志 API 发送未捕获异常的报告到日志文件。

如果不安装默认的处理器，默认的处理器为空。但是，如果不为独立的线程安装处理器，此时的处理器就是该线程的 ThreadGroup 对象

###### 线程组

线程组是一个可以统一管理的线程集合。默认情况下，创建的所有线程属于相同的线程组，但是，也可能会建立其他的组。现在引入了更好的特性同于线程集合的操作，建议不要在自己的程序中使用线程组

ThreadGroup 类实现 Thread.UncaughtExceptionHandler 接口。它的 uncaughtException 方法做如下操作：

* 如果该线程组有父线程组，那么父线程组的 uncaughtException 方法被调用
* 否则，如果 Thread.getDefaultExceptionHandler 方法返回一个非空的处理器，则调用该处理器
* 否则，如果 Throwable 是 ThreadDeath 的一个实例，什么都不做
* 否则，线程的名字以及 Throwable 的栈轨迹被输出到 System.err 上

###### 线程局部变量

static final 域无法保证线程安全，要为每个线程构造一个实例，可以使用

```java
public static final ThreadLocal<SimpleDateFormat> dateFormat = ThreadLocal.withInitial(
	()->new SimpleDateFormat("yyyy-MM-dd")
);
```

会返回属于当前线程的实例。

##### 线程同步

如果一个类的对象在多线程程序中没有导致竞争状态，则称为这样的类为线程安全的。

###### 锁对象

*Lock* 为避免竞争状态，应该防止多个线程同时进入程序的某一特定部分，程序中的这部分称为临界区。可以在执行前加锁，对于实例方法要给调用该方法的对象加锁。对于静态方法，要给这个类加锁。如果一个线程调用一个对象上的同步实例方法（静态方法），首先给该对象（类）加锁，然后执行该方法，最后解锁。在解锁之前，另一个调用那个对象（类）中方法的线程将被阻塞，直到解锁

使用 Lock 对象时，必须使用 finally 语句，并且在第一行释放锁。可以使用 tryLock() 方法来获取非阻塞锁。成功获得锁后返回 true，失败返回 false，可以指定超时参数

```
if (myLock.tryLock(100, TimeUnit.MILLISECONDS)) {
		try {} finlly { myLock.unlock(); }	
}
```

*TimeUnit* 是一个枚举类型，可以取的值包括 SECONDS、MILLISECONDS、MICROSECONDS、NANOSECONDS

lock() 方法不能被中断，如果一个线程在等待获得一个锁时被中断，中断线程在获得锁之前一直处于阻塞状态。如果出现死锁，lock 方法就无法终止。如果调用带有超时参数的 tryLock()，那么如果线程在等待期间被中断，将抛出 *InterruptedException* 异常。

###### 条件对象

Condition 通常，线程进入临界区，却发现在某一条件满足之后它才能执行。要使用一个条件对象来管理那些已经获得了一个锁但是却不能做有用工作的线程。

当一个线程拥有某个条件的锁时，它仅仅可以在该条件上调用 await、signalAll、signal 方法。

可以指定等待超时时间等待，如果等待的线程被中断，await 方法将抛出一个 InterruptedException 异常。

###### synchronized 关键字

Lock 和 Condition 接口为程序提供了高度的锁定控制，大多数情况下，并不需要那样的控制，可以使用 java 语言内部的机制，从 1.0 开始，java 中的每一个对象都有一个内部锁，如果一个方法用 synchronized 关键字声明，那么对象的锁江保护整个方法。即要调用该方法，线程必须获得内部的对象锁

```java
synchronized void method() {}
// 等价
void method() {
	this.intrinsicLock.lock();
	try {
			
	} finally {
			this.intrinsicLock.unlock();
	}
}
```

内部对象锁只有一个相关条件，wait 方法添加一个线程到等待集中，notifyAll 或 notify 方法解除等待线程的阻塞状态。即在对象上调用 wait 或 notifyAll 方法，等价于条件对象上的 await、signalAll 方法

将静态方法声明为 synchronized 也是合法的，如果<u>调用静态 synchronized 方法，该方法获得相关的类对象的内部锁</u>，因此其他线程可以调用同一个类的这个或任何其他的同步静态方法。

###### 同步阻塞

代码块级别的锁，进一步缩小锁范围。

```java
synchronized(obj) {}
```

在对象中可以持有一个 object 来使用它的对象锁，实现客户端锁定。或使用 this 来实现方法中代码块加锁

###### 应用场合

锁与条件的关键区别：

* 锁用来保护代码片段，任何时刻只能有一个线程执行被保护的代码
* 锁可以管理试图进行被保护代码段的线程
* 锁可以拥有一个或多个相关的条件对象
* 每个条件对象管理那些已经进入被保护的代码段但还不能运行的线程。

内部锁和条件存在局限：

* 不能中断一个正在试图获得锁的线程
* 试图获得锁时不能设定超时
* 每个锁仅有单一的条件，可能是不够的

最好不要自己处理竞争条件，使用 java.util.concurrent 包中的一种机制。锁范围越小越好，能使用 synchronized  关键字处理时优先。

###### 监视器概念

锁和条件是线程同步的强大工具，但是，严格区分它们不是面向对象的。监视器（monitor）具有：

* 监视器是只包含私有域的类
* 每个监视器类的对象有一个相关的锁
* 使用该锁对所有方法进行加锁。即如果客户端调用 obj.method()，那么 obj 对象的锁是在方法调用开始时自动获得，并且当方法返回时自动释放该锁。因为所有的域是私有的，这样可以确保一个线程在对对象操作时，没有其他线程能访问该域
* 该锁可以有任意多个相关条件

###### volatile

有时，仅仅为了读写一个或两个实例域就使用同步，显得开销过大了。volatile 关键字为实例域的同步访问提供了免锁机制：如果声明一个域为 volatile 那么编译器和虚拟机就知道该域是可能被另一个线程并发更新的。

voliatile 域不提供原子性

###### 读写锁

*ReentrantReadWriteLock* 使用流程

```java
// 构造一个 ReentrantReadWriteLock 对象
private ReentrantReadWriteLock rwl = new ReentrantReadWriteLock();
// 抽取读写锁
private Lock readLock = rwl.readLock();
private Lock wirteLock = rwl.wirteLock();
```

##### 使用阻塞队列代替底层构建

对于实际编程来说，应该尽可能远离底层结构，对于许多线程问题，可以通过使用一个或多个队列以实现序列化以避免同步。

当试图向队列添加元素而队列已满，或试图从队列移除元素而队列为空时，阻塞队列导致线程阻塞。并发包提供了阻塞队列的几个变种。

*阻塞队列*

|  方法   |        正常动作         |            特殊情况下动作             |
| :-----: | :---------------------: | :-----------------------------------: |
|   add   |      添加一个元素       |  队列满，抛出 IllegalStateException   |
|   put   |      添加一个元素       |            队列满，则阻塞             |
|  offer  | 添加一个元素并返回 true |         队列满，则返回 false          |
|  poll   |    移除并返回头元素     |           队列空，返回 null           |
| remove  |    移除并返回头元素     | 队列空，则抛出 NoSuchElementException |
|  take   |  移除并返回队列头元素   |            队列空，则阻塞             |
|  peek   |    返回队列的头元素     |           队列空，返回 null           |
| element |    返回队列的头元素     |  队列空，抛出 NoSuchElementException  |

###### LinkedBlockingQueue

默认情况下容量没有上限，可以选择指定最大容量

###### LinkedBlockingDeque

双端队列

###### ArrayBlockingQueue

需要指定容量，并且可以指定是否需要公平性

###### PriorityBlockingQueue

优先级队列，没有容量上限。

###### DelayQueue

实现了 Delayed 接口

getDelay() 方法返回对象的残留延迟，负值表示延迟已经结束。元素只有在延迟用完的情况下才能从 DelayQueue 移除。必须实现 compareTo 方法，DelayQueue 使用该方法对元素进行排序

###### LinkedTransferQueue

SE 7 增加，允许生产者线程等待，直到消费者准备就绪可以接收一个元素。如果生产者调用 q.transfer(item) 这个调用会阻塞，直到另一个线程将元素删除

##### 线程安全集合

java.util.concurrent 包提供了映射、有序集和队列：*ConcurrentHashMap*、*ConcurrentSkipListMap*、*ConcurrentSkipListSet* 和 *ConcurrentLinkedQueue*

这些集合使用复杂的算法，通过允许并发地访问数据结构的不同部分来使竞争极小化。确定这样的集合当前的大小通常需要遍历

集合返回弱一致性的迭代器。迭代器不一定反映出它们被构造之后的所有的修改，它们不会将同一个值返回两次，也不会抛出 ConcurrentModificationException 异常。集合如果在迭代器之后发生改变，迭代器将抛出一个 ConcurrentModificationException 异常

###### ConcurrentHashMap

不允许有 null 值。

###### CopyOnWriteArrayList

线程安全的列表，其中所有的修改线程对底层数组进行复制。当构建一个迭代器的时候，它包含一个对当前数组的引用。如果数组后来被修改了，迭代器仍然引用旧数组，但集合的数组已经被替换了

###### ConpyOnWriteArraySet

线程安全的集合

##### 线程池

###### 线程池

应该使用线程池代替创建线程，将 Runnable 对象交给线程池。线程池用于高效执行任务，线程池是管理并发执行任务个数的理想方法，java 提供 *Executors* 来执行线程池中的任务，提供 ExecutorService 来管理和控制任务，ExecutorService 是 Executor 的子接口 *java.util.concurrent.Executor.java*

在使用连接池时应该做的事：

1. 调用 *Executors* 类中静态方法创建 *ExecutorService*
2. 调用 submit 提交  Runnable 或 Callable 对象
3. 如果想要取消一个任务，或如果提供 Callable 对象，需要保存好返回的 Future 对象
4. 当不再提及任何任务时，调用 shutdown

######预定执行

ScheduledExecutorService 接口具有为预定执行或重复执行任务而设计的方法。允许使用线程池机制的 java.util.Timer 的泛化。*Executors* 支持生成 ScheduledExecutorServlet 接口对象

可以预定 Runnable 或 Callable 在初始的延迟之后只运行一次或周期性执行。

#### 信号量（对共享资源进行访问控制的对象）

可以使用信号量来限制访问一个共享资源的线程数，在访问资源前，线程必须从信号量获取许可。在访问完资源后，这个线程将许可返回给信号量
*java.util.concurrent.Semaphore.java*

```javascript 1.8
Semaphore(numberOfPermits: int)         // 创建一个具有指定数目的许可的信号量。公平策略为假
Semaphore(nunberOfPermits: int, fair: boolean)          // 创建一个具有指定数目的许可及公平策略的信号量
acquire(): void             // 从该信号量获取一个许可，如果许可不可用，线程会阻塞等待
release(): void             // 释放一个许可返回给信号量
```









