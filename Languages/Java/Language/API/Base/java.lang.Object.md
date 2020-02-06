*java.lang.Object*

```java
// 返回对象类对象
Class getClass();
// 如果两个对象指向同一块存储区域，方法返回 true；否则方法返回 false。在自定义的类中，应该覆盖这个方法
boolean equals(Object other);
// 返回描述该对象值的字符串
String toString() { return getClass().getName() + "@" + Integer.toHexString(hashCode()); }
// 解除那些在该对象上调用 wait 方法的线程阻塞状态，该方法只能在同步方法或同步块内部调用。如果当前线程不是对象锁的持有者，抛出 IllegalMonitorStateException
final native void notifyall();
// 随机选择一个在该对象上调用 wait 方法的线程，解除其阻塞状态，该方法只能在同步方法或同步块中调用，如果当前线程不是对象锁的持有者，该方法抛出 IllegaMonitorStateException
final void native void notifyAll();
// 使当前线程等待，直到它被唤醒或被中断，如果任何新车在此之前中断了当前线程，或者当前线程正在等待将抛出 InterruptedException 异常，如果当前线程不是对象锁的所有者将抛出 IllegalMonitorStateException 异常,只能在同步方法中调用
final void wait() throws InterruptedException;
// 导致线程进入等待状态直到它被通知或者经过指定的时间。这些方法只能在一个同步方法中调用。如果当前线程不是对象锁持有者，该方法抛出一个 IllegalMonitorStateException 异常
final native void wait(long timeoutMillis) throws InterruptedException;
final void wait(long timeoutMillis, int nanos) throws InterruptedException;
// 返回这个对象的散列码。散列码可以是任何整数，包括正数或负数。equals 和 hashCode 的定义必须兼容，即 x.equals(y) 为 true，x.hashCode() 必须等于 y.hashCode()
int hashCode();
```