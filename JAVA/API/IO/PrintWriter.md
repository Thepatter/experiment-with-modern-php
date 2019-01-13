## java.io.PrintWriter

* `PrintWriter(Writer out)`

* `PrintWriter(Writer)`

  创建一个向给定的写出器写出的新的 `PrintWriter`

* `PrintWriter(String filename, String encoding)`

* `PrintWriter(File file, String encoding)`

  创建一个使用给定的编码方式向给定的文件写出的新的 `PrintWriter`

* `void print(Object obj)`

    通过打印从 `toString` 产生的字符串来打印一个对象

* `void print(String s)`

    打印一个包含 `Unicode` 码元的字符串

* `void println(String s)`

    打印一个字符串，后面紧跟一个行终止符。如果这个流处于自动冲刷模式，那么就会冲刷这个流

* `void print(char[] s)`

    打印在给定的字符串中的所有 `Unicode` 码元

* `void print(char c)`

    打印一个 `Unicode` 码元

* `void print(int i)`

* `void print(long l)`

* `void print(float f)`

* `void print(double d)`

* `void print(boolean b)`

    以文本格式打印给定的值

* `void printf(String format, Object... args)`

    按照格式字符串指定的方式打印给定的值。

* `boolean checkError()`

    如果产生格式化或输出错误，则返回 `true`。一旦这个流碰到了错误，它就受到了污染，并且所有对 `checkError` 的调用都将返回 `true`