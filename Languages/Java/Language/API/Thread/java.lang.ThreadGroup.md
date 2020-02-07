*java.lang.ThreadGroup*
```java
void uncaughtException(Thread t, Throwable e);

// 如果有父线程组，调用父线程组的这一方法，或者，如果 `Thread` 类有默认处理器，调用该处理器，否则，输出栈轨迹到标准错误流上（如果  e 是一个 ThreadDeath 对象，栈轨迹是被禁用的。ThreadDeath 对象由 stop 方法产生，而该方法已经过时）

```