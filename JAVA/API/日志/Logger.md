## java.util.logging.Logger

* `Logger getLogger(String loggerName)`

* `Logger getLogger(String loggerName, String bundleName)`

  获得给定名字的日志记录器。如果这个日志记录器不存在，创建一个日志记录器

  参数：`loggerName`    具有层次结构的日志记录器名。例如：`com.mycompany.myapp`

  ​	   `bundleName`     用来查看本地消息的资源包名

* `void servere(String message)`

* `void warning(String message)`

* `void info(String message)`

* `void config(String message)`

* `void fine(String message)`

* `void finer(String message)`

* `void finest(String message)`

  记录一个由方法名和给定消息指示级别的日志记录

* `void entering(String className, String methodName)`

* `void entering(String className, String methodName, Object param)`

* `void entering(String className, String methodName, Object[] param)`

* `void exiting(String className, String methodName)`

* `void exiting(String className, String methodName, Object result)`

  记录一个描述进入/退出方法的日志记录，其中应该包括给定参数和返回值

* `void throwing(String className, String methodName, Throwable t)`

  记录一个描述抛出给定异常对象的日志记录

* `void log(Level level, String message)`

* `void log(Level level, String message, Object obj)`

* `void log(Level level, String message, Object[] objs)`

* `void log(Level level, String message, Throwable t)`

  记录一个给定级别和消息的日志记录，其中可以包括对象或者可抛出对象。要想包括对象，消息中必须包含格式化占位符号 ｛0｝、｛1｝等

* `void logp(Level level, String className, String methodName, String message)`

* `void logp(Level level, String className, String methodName, String message, Object obj)`

* `void logp(Level level, String className, String methodName, String message, Object[] objs)`

* `void logp(Level level, String className, String methodName, String message, Throwable t)`

  记录一个给定级别、准确的调用者信息和消息的日志记录，其中可以包括对象和可抛出对象

* `void logrb(Level level, String className, String methodName, String bundleName, String message)`

* `void logrb(Level level, String className, String methodName, String bundleName, String message, Object obj)`

* `void logrb(Level level, String className, String methodName, String bundleName, String message, Object[] objs)`

* `void logrb(Level level, String className, String methodName, String bundleName, String message, Throwable t)`

  记录一个给定级别、准备的调用者信息、资源包名和消息的日志记录，其中可以包括对象或可抛出对象

* `Level getLevel()`

* `void setLevel(Level l)`

  获得和设置这个日志记录器的级别

* `Logger getParent()`

* `void setParent(Logger l)`

  获得和设置这个日志记录器父日志记录器

* `Handler[] getHandlers()`

  获得这个日志记录器的所有处理器

* `void addHandler(Handler h)`

* `void removeHandler(Handler h)`

  增加或删除这个日志记录器的一个处理器

* `boolean getUseParentHandlers()`

* `void setUseParentHandlers(boolean b)`

  获得和设置 “use parent handler" 属性。如果这个属性是 true，则日志记录器会将全部的日志记录转发给它的父处理器

* `Filter getFilter()`

* `void setFilter(Filter f)`

  获得和设置这个日志记录器的过滤器