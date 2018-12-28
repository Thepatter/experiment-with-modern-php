## Java IO  API 相关
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

 
