*javax.swing.Timer*
```java
Timer(int interval, ActionListener listener);

// 构造一个定时器，每隔 `interval` 毫秒通告 `listener` 一次

void start();

// 启动定时器。一旦启动成功，定时器将调用监听器的 `actionPerformed`

void stop();

// 停止定时器。一旦停止成功，定时器将不再调用监听器的 `actionPerformed`

```