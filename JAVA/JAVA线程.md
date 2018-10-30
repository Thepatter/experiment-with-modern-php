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