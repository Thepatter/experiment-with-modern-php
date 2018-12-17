## Java IO  API 相关

### java.util.Scanner

* `Scanner (InputStram in)`: 用给定的输入流创建一个 `Scanner` 对象

* `Scanner (File f)`: 构造一个从给定胃镜读取数据的 `Scanner`

* `Scanner (String data)`: 构造一个从给定字符串读取数据的 `Scanner`

* `String nextLine()`: 读取输入的下一行内容

* `String next()`: 读取输入的下一个单词（以空格作为分隔符）

* `int nextInt()`: 读取输入的一个 int

* `double nextDouble`: 读取下一个 double

* `boolean hasNext()`: 检测输入中是否还有其他单词

* `boolean hasNextDouble()`: 检测是否还有表示整数或浮点数的下一个字符串序列。

### java.lang.System

* `static Console console()`: 
如果有可能进行交互操作，就通过控制台窗口为交互的用户返回一个 `Console` 对象，否则返回 null。对于任何一个通过控制台窗口启动的程序，
都可使用 `Console` 对象。否则，其可用性将与所使用的系统有关

### java.io.Console

* `static char[] readPassword(String prompt, Object ...args)`

* `static String readLine(String prompt, Object ...args)`
显示字符串 `prompt` 并且读取用户输入，直到输入行结束。`args` 参数可以用来提供输入格式。

### java.io.PrintWriter

* `PrintWriter(String fileName)`: 构造一个将数据写入文件的 `PrintWriter` 。文件名由参数指定

### java.nio.file.Paths

* `static Path get(String pathname)`: 根据给定的路径名构造一个 Path

 
