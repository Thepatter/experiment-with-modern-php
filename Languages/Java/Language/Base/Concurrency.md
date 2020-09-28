##### 线程属性

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

优先级队列，没有容量上限。具有可阻塞的读取操作。

###### DelayQueue

实现了 Delayed 接口，是一个无界的 *BlockingQueue*，用于放置实现 Delayed 接口的对象，其中的对象只能在其到期时才能从队列中取走。这种队列是有序的，即队列头的延迟到期的时间最长。如果没有任务延迟到期，那么就不会有任务头元素，并且 poll() 将返回 null（不能将 null 放回队列）

必须实现 compareTo 方法，DelayQueue 使用该方法对元素进行排序

Delay 接口的 getDelay()，返回延迟到期时间，或者延迟在多长时间已到期（负值），在 getDelay() 中，希望使用的单位是作为 *TimeUnit* 传递进来的，使用它将当前时间与触发时间之间的差转换为调用者要求的单位，而无需知道这些单位是什么。

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
