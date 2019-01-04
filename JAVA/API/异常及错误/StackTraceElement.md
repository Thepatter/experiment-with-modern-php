## java.lang.StackTraceElement

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