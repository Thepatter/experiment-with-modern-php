## JAVA 多线程
### JAVA 线程
#### JAVA 中线程的定义
Java内部支持多线程，在一个程序中允许同时允许多个任务。可以在程序中创建附加的线程以执行并发任务，在JAVA中，每个任务都是`Runnable`接口的实例
一个任务类必须实现 `Runnable` 接口，任务必须从线程运行
```java
public class TaskClass implements Runnable {
    @Override
    public function run(); // 线程执行才方法
}
// 构建任务
TaskClass task = new TaskClass();
// 运行任务
Thread thread = new Thread(task);
thread.start();
```
任务中的 `run` 方法指明如何完成这个任务，Java 虚拟机会自动调用该方法，无需特意调用它，直接调用 `run` 只是在同一个线程中执行该方法，而没有新线程启动

#### Thread 类
__Thread 类包含为任务而创建的线程的构造方法，以及控制线程的方法__
```java
Thread()    // 创建一个空的线程
Thread(task: Runnable)      // 为指定任务创建一个线程
start(): void       // 开始运行线程任务
isAlive(): boolean      // 测试线程当前是否在运行
setPriority(p: int): void       // 为该线程指定优先级 p (取值从 1 到 10) 
join(): void        // 等待该线程结束
sleep(millis: long)： void   // 让线程休眠，毫秒
yield(): void       // 线程暂停并允许其他线程执行
interrupt(): void   // 中断该线程
```
### 线程池
__线程池用于高效执行任务，线程池是管理并发执行任务个数的理想方法，Java 提供 Executor 接口来执行线程池中的任务，提供 ExecutorService 
来管理和控制任务，ExecutorService 是 Executor 的子接口__

*java.util.concurrent.Executor.java*
```java
execute(Runable object): void      // 执行可运行任务
```

*java.util.concurrent.Executors.java*
```
newFixedThreadPool(numberOfThreads: int): ExecutorService   // 创建指定数线程池
newCachedThreadPool(): ExecutorService  // 创建一个线程池，它会在必要是创建新的线程，优先重用之前创建的线程
```

*java.util.concurrent.ExecutorService.java*
```java
shutdown(): void        // 关闭执行器，但是允许执行器中的任务执行完，一旦关闭，则不再接收新的任务
shutdownNow(): List<Runnable>  // 立刻关闭执行器，即使池中还有未完成的线程。返回未完成任务的列表
isShutdown(): boolean       // 如果执行器已经关闭，则返回 true
isTerminated(): boolean         // 如果池中的所有任务终止，则返回 true
```

### 线程同步

**如果一个类的对象在多线程程序中没有导致竞争状态，则称为这样的类为线程安全的**

##### synchronized 关键字

为避免竞争状态，应该防止多个线程同时进入程序的某一特定部分，程序中的这部分称为临界区。可以使用关键字 `synchronized` 来同步方法，以便一次只有一个线程可以访问这个方法。

```java
public synchronized void deposit() {}
```

也可以在执行前加锁，对于实例方法要给调用该方法的对象加锁。对于静态方法，要给这个类加锁。如果一个线程调用一个对象上的同步实例方法（静态方法），首先给改对象（类）加锁，然后执行该方法，最后解锁。在解锁之前，另一个调用那个对象（类）中方法的线程将被阻塞，直到解锁

##### 同步语句

调用一个对象上的同步实例方法，需要给该对象加锁。而调用一个类上的同步静态方法，需要给该类加锁。当执行方法中某一个代码块时，同步语句不仅可用于对 this 对象加锁，而且可用于对任何对象加锁。这个代码块称为同步块。

```java
synchronized (expr) {
    statements;
}
```

表达式 `expr` 求值结果必须是一个对象的引用。如果对象已经被另一个线程锁定，则在解锁之前，该线程将被阻塞。当获准对一个对象加锁时，该线程执行同步块中的语句，然后解除给对象所加的锁

#### 利用加锁同步：显式地采用锁和状态同步线程

一个锁是一个 `Lock` 接口的实例，它定义了加锁和释放锁的方法。锁也可以使用 `newCondition()` 方法来创建任意个数的 `Condition` 对象，用来进行线程间通信。`ReentrantLock` 是 `Lock` 的一个具体实现，用于创建相互排斥的