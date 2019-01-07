## java.lang.Object

* `Class getClass()` 				 

  返回包含对象信息的类对象。

* `boolean equals(Object otherObject)`                                     

  比较两个对象是否相等，如果两个对象指向同一块存储区域，方法返回 true；否则方法返回 false。在自定义的类中，应该覆盖这个方法

* `String toString()`                             

  返回描述该对象值的字符串。在自定义的类中，应该覆盖这个方法

- `void notifyAll()`

  解除那些在该对象上调用 `wait` 方法的线程的阻塞状态，该方法只能在同步方法或同步块内部调用。如果当前线程不是对象锁的持有者，该方法抛出一个 `IllegalMonitorStateException` 异常

- `void notify()`

  随机选择一个在该对象上调用 `wait` 方法的线程，解除其阻塞状态。该方法只能再一个同步方法或同步块中调用。如果当前线程不是对象锁的持有者，该方法抛出一个 `IllegalMonitorStataException` 异常

- `void wait()`

  导致线程进入等待状态直到它被通知。该方法只能在一个同步方法中调用。如果当前线程不是对象锁的持有者，该方法抛出一个 `IllegalMonitorStateException` 异常

- `void wait(long millis)`

- `void wait(long millis, int nanos)`

  导致线程进入等待状态直到它被通知或者经过指定的时间。这些方法只能在一个同步方法中调用。如果当前线程不是对象锁持有者，该方法抛出一个 `IllegalMonitorStateException` 异常

  `millis` 	        毫秒数

  `nanos`	        纳秒数， < 1000000

* `int hashCode()`

    返回这个对象的散列码。散列码可以是任何整数，包括正数或负数。`equals` 和 `hashCode` 的定义必须兼容，即 `x.equals(y)` 为 true，`x.hashCode()` 必须等于 `y.hashCode()`