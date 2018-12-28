## 异常相关 API

### java.lang.Throwable

* `void printStackTrace()`

  将 `Throwable` 对象和栈的轨迹输出到标准错误流

* `Throwable()`

  构造一个新的 `Throwable` 对象， 这个对象没有详细的描述信息

* `Throwable(String message)`

  构造一个新的 `throwable` 对象，这个对象带有特定的详细描述信息。习惯上，所有派生的异常类都支持一个默认的构造器和一个带有详细描述信息的构造器

* `String getMessage()`

  获得 `Throwable` 对象的详细描述信息

* `Throwable(Throwable cause)`

* `Throwable(String message, Throwable cause)`

  用给定的原因构造一个 `Throwable` 对象

* `Throwable initCause(Throwable cause)`

  将这个对象设置为原因。如果这个对象已经被设置为原因，则抛出一个异常，返回 this 引用

* `Throwable getCause()`

  获得设置为这个对象的原因的异常对象。如果没有设置原因，则返回 null

* `StackTraceElement[] getStackTrace()`

  获得构造这个对象时调用堆栈的跟踪

* `void addSuppressed(Throwable t)`

  为这个异常增加一个抑制异常。这出现在带资源的 try 语句中，其中 t 是 close 方法抛出的一个异常

* `Throwable[] getSuppressed()`

  得到这个异常的所有 “抑制“ 异常。一般来说，这些是带资源的 `try` 语句中的 `close` 方法抛出的异常

### java.lang.Exception

* `Exception(Throwable cause)`

* `Exception(String message, Throwable cause)`

  用给定的原因构造一个异常对象

### java.lang.RuntimeException

* `RuntimeException(Throwable cause)`

* `RuntimeException(String message, Throwable cause)`

  用给定的原因构造一个 `RuntimeException` 对象

### java.lang.StackTraceElement

* `String getFileName()`

  返回这个元素运行时对应的源文件名。如果这个信息不存在，返回null

* `int getLineNumber()`

  返回这个元素运行时对应的源文件行数。如果这个信息不存在，则返回 -1

* `String getClassName()`

  返回这个元素运行时对应的类的完全限定名

* `String getMethodName()`

  返回这个元素运行时对应的方法名。构造器名是<init>; 静态初始化器名是 <clinit>。这里无法区分同名的重载方法

* `boolean isNativeMethod()`

  如果这个元素运行时在一个本地方法中，则返回 true

* `String toString()`

  如果存在的话，返回一个包含类名，方法名，文件名和行数的格式化字符串