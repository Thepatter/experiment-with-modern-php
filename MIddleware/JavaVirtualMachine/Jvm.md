### JVM

#### JDK

##### 安装

1. 官网下载

2. 解压到指定路径

3. 配置系统变量指定

   *~/.bash_profile*

   ```shell
   export JAVA_HOME=/path/to/jdk
   export PATH=jdk/bin:$PATH
   ```

4. 获取源码

   JDK 附带了一个 $jdk_home/lib/src.zip 的源代码

   ```shell
   mkdir javasrc
   cd javasrc
   jar xvf jdk_home/lib/src.zip
   ```

5. 检验安装

   ```
   javac --version
   ```

##### 命令行使用

1. 使用 javac 命令编译源文件为字节码 class 文件

2. 使用 Java 运行 class 文件，运行时类包名要与路径名匹配，否则会找不到主类，

   ```shell
   # 类名 jdbc.mysql.CityCurd
   java java/mysql/CityCurd
   ```

##### 文本文件和字符集

###### 源文件的字符编码

作为程序员，要与 java 编译器交互，这种交互需要通过本地系统的工具来完成。例如，可以使用中文版的记事本来写 java 源代码文件，但这样写出来的源码不是随处可用的，因为它们使用的是本地的字符编码，只有编译后的 class 文件才能随处使用，它们会自动地使用 modified UTF-8 编码来处理标识符和字符串。即在程序编译和运行时，依然有3种字符编码参与其中：

* 源文件：本地编码
* 类文件：modified UTF-8
* 虚拟机：UTF-16

使用 -encoding 标记来设定源文件的字符编码

```shell
javac -encoding UTF-8 Myfile.java
```

为了使源文件能够到处使用，必须使用普通的 ASCII 编码。即，需要将所有非 ASCII 字符转换成等价的 Unicode 编码。JDK 包含一个工具---native2ascii，可以用它来将本地字符编码转换成普通的 ASCII。这个工具直接将输入中的每一个非 ASCII 字符替换为一个  \u 加 4 位十六进制数字的 Unicode 值。使用 native2ascii 时，需要提供输入和输出文件的名字

```shell
native2ascii Myfile.java Myfile.temp
```

用 -reverse 选项来进行逆向转换

```
native2ascii -reverse Myfile.temp Myfile.java
```

用 -encoding 选项指定另一种编码

```java
native2ascii -encoding UTF-8 Myfile.java Myfile.temp
```

