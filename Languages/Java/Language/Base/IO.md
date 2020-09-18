### Java I/O 

自 1.0 以来，Java I/O 类库发生了明显改变，在原来面向字节的类中添加了面向字符和基于 Unicode 的类，1.4 中，添加了 nio 类库用于改进性能及功能。

#### IO 类库

*java.io* 包中包含了很多类用于进行 I/O 操作

##### File

既能代表一个特定文件的名称，又能代表一个目录下的一组文件的名称

可以用 *File* 对象创建新的目录或尚不存在的目录路径和查看文件特性（大小、最后修改日期，读写，删除文件）。如果以 *FileOutputStream* 或 *FileWriter* 打开，那么它肯定会被覆盖，应该先使用 *File* 判断

##### 字节输入输出

通过继承，任何自 *Inputstream*（字节）或 *Reader*（字符）派生类都可以使用 read 方法读取单个字节或字节数组；任何自 *OutputStream* 或 *Writer* 派生类都可以使用 writer 方法写单个字节或字节数组。很少使用单一的类来创建流对象，而是通过叠合多个对象来提供所期望的功能，即创建单一的结果流，需要创建多个对象

在 Java 1.0 中，类库的设计者首先限定与输入有关的所有类都应该从 *InputStream* 继承，而与输出有关的所有类都应该从 *OutputStream* 继承

字节的输入/输出被抽象为流：*InputStream*/*OutputStream* 子类指定将那种数据源（文件、字节数组、字符串等）抽象为流，*FilterInputStream*/*FilterOutputStream* 的子类装饰流，指定如何读取/写入流中的数据（缓冲、行、基本类型）

*   使用 *DataOutputStream* 写字符串且让 *DataInputStream*  能读取的可靠做法是使用 UTF-8 编码

###### *InputStream*

*InputStream* 作用是用来表示那些从不同数据源产生输入的类。每种数据源都有相应的 *InputStream* 子类。和 *FilterInputStream* 为装饰器类（把属性或有用的接口与输入流连接在一起）提供基类

*输入源及装饰器基类*

|           子类            |                             功能                             |                            构造器                            |                        使用                         |
| :-----------------------: | :----------------------------------------------------------: | :----------------------------------------------------------: | :-------------------------------------------------: |
|  *ByteArrayInputStream*   |                 将内存缓冲区当作 InputStream                 |                    缓冲区，字节将从中取出                    |  作为一种数据源；与 FilterInputStream 对象组合使用  |
| *StringBufferInputStream* | 将 String 转换成 InputStream。已废弃（可能无法正确转换为字节）使用 *StringReader* |                字符串，底层使用 StringBuffer                 |      数据源；与 FilterInputStream 对象组合使用      |
|     *FileInputStream*     |                          用于文件读                          |              文件名字符串，File、FileDescriptor              |      数据源；与 FilterInputStream 对象组合使用      |
|    *PipedInputStream*     |      产生用于写入 PipedOutputStream 的数据，实现管道化       |                      PipedOutputStream                       | 作为多线程数据源；与 FilterInputStream 对象组合使用 |
|   *SequenceInputStream*   |     将两个或多个 InputStream 对象转换成单一 InputStream      | 两个 InputStream 对象或一个容纳 InputStream 对象的容器 Enumeration |    作为数据源；与 FilterInputStream 对象组合使用    |
|   *FileterInputStream*    |    抽象类，作为装饰器接口，与其他 InputStream 类组合使用     |                                                              |        把属性或有用的接口与输入流连接在一起         |

###### *OutputStream*

该类决定了输出的目的地

|         子类          |                             功能                             |                 构造器                  |                         使用                         |
| :-------------------: | :----------------------------------------------------------: | :-------------------------------------: | :--------------------------------------------------: |
| ByteArrayOutputStream |    在内存中创建缓冲区，所有送往流的数据都要放置在此缓冲区    |            初始大小（可选）             |    指定数据目的地；与 FilterOutputStream 组合使用    |
|   FileOutputStream    |                           写入文件                           | 文件名字符串，File 对象，FileDescriptor |   指定数据的目的地；与 FilterOutputStream 组合使用   |
|   PipedOutputStream   | 任何写入其中的信息都会自动作为相关 PipeInputStream 输出，实现管道化 |            PipedInputStream             | 指定多线程数据目的地；与 FilterOutputStream 组合使用 |
|  FilterOutputStream   |     抽象类，作为装饰器接口，与其他 OutputStream 组合使用     |                                         |                                                      |

###### *RandomAccessFile*

随机读写，不在 *InputStream*、*OutputStream* 继承层次结构，不支持装饰。实现了 *DataInput*、*DataOutput* 接口。工作方式类似于把 *DataInputStream* 和 *DataOutputStream* 组合使用，并添加了一些方法。支持查找当前所处的文件位置（getFilePointer）及将文件移动到新位置（seek），支持随机读和读写模式，但不支持只写模式，拥有读取基本类型和 UTF-8 字符串的方法

1.4 之后，大多数功能由 nio 存储映射文件取代

###### 管道流

管道流一般用于多线程环境下的任务之间的通信

###### 过滤器

抽象 Filter 是所有装饰器类的基类。*FilterInputStream* 和 *FilterOutputStream* 是用来提供装饰器类接口以控制特定 *InputStream* 和 *OutputStream* 的两个类。

*   *FilterInputStream* 子类及功能

    |          子类           |                         功能                         |            构造参数            |                         使用                          |
    | :---------------------: | :--------------------------------------------------: | :----------------------------: | :---------------------------------------------------: |
    |    *DataInputStream*    |   从流中读取基本类型，搭配 *DataOutputStream* 使用   |         *InputStream*          |          包含用于读取基本类型数据的全部接口           |
    |  *BufferedInputStream*  |                       缓冲区读                       | *InputStream* 或可选缓冲区大小 |          向进程中添加缓冲区，与接口对象搭配           |
    | *LineNumberInputStream* |                    跟踪输入流行号                    |         *InputStream*          |              仅增加行号，与接口对象搭配               |
    |  *PushbackInputStream*  | 能弹出一个字节的缓冲区，可以将读到的最后一个字符回退 |         *InputStream*          | 作为编译器的扫描器，Java 编译器使用，其他场景不会使用 |

*   *FilterOutputStream* 子类及功能

    |          子类          |                             功能                             |             构造参数              |      使用      |
    | :--------------------: | :----------------------------------------------------------: | :-------------------------------: | :------------: |
    |   *DataInputStream*    | 搭配 *DataInputStream* 使用，将基本类型及 String 格式化输出到流中 |          *OutputStream*           | wirte 开头方法 |
    |     *PrintStream*      | 格式化输出基本数据类型及 String 对象，可能会有问题（捕捉了所有 IOException，必须使用 checkError，且未完全国际化） |          *OutputStream*           | print/println  |
    | *BufferedOutputStream* |        修改过的 *OutputStream*，对数据流使用缓冲技术         | *OuputStream*，可选指定缓冲区大小 |                |

##### 字符的输入输出

*Reader*//*Writer* 提供兼容 Unicode 与面向字符的 I/O  功能。*Reader* 和 *Writer* 的继承层次结构主要是为了国际化，在 I/O 操作中支持 Unicode（Java 中 char 也是 16 位 Unicode），*Stream* 的 I/O 流仅支持 8 位的字节流。不能很好处理 16 位的 Unicode 字符。

与输入/出流类似，*Reader*/*Writer* 子类及适配器指定了如何将字节流转换字符流，*FilterReader*/*FilterWriter* 及子类则装饰流的如何操作流的行为

*字符与字节对应装饰器及过滤器类*

|                字节装饰                 |       字符装饰       |               字节过滤                |             字符过滤             |
| :-------------------------------------: | :------------------: | :-----------------------------------: | :------------------------------: |
|              *InputStream*              | *InputStreamReader*  |          *FilterInputStream*          |          *FilterReader*          |
|             *OutputStream*              | *OutputStreamWriter* |         *FilterOutputStream*          | *FilterWriter*（抽象类没有子类） |
|            *FileInputStream*            |     *FileReader*     |         *BufferedInputStream*         |         *BufferedReader*         |
|           *FileOutputStream*            |     *FileWriter*     |        *BufferedOutputStream*         |         *BufferedWriter*         |
| *StringBufferInputStream*（Deprecated） |    *StringReader*    |           *DataInputStream*           |         *BufferedReader*         |
|                                         |    *StringWriter*    |             *PrintStream*             |          *PrintWriter*           |
|         *ByteArrayInputStream*          |  *CharArrayReader*   | *LineNumberInputStream*（Deprecated） |        *LineNumberReader*        |
|         *ByteArrayOutputStream*         |  *CharArrayWriter*   |           *StreamTokenizer*           |        *StreamTokenizer*         |
|            *PipeInputStream*            |     *PipeReader*     |         *PushbackInputStream*         |         *PushbackReader*         |
|           *PipedOutputStream*           |    *PipedWriter*     |                                       |                                  |

###### 装饰器

*Reader/Writer* 及其子类有着与 *InputStream*/*OutputStream* 对应的类，*FilterReader*/*FilterWriter* 有着与 *FilterInputStream*/*FilterOutputStream* 对应的子类

###### 适配器

当需要把字节流和字符流结合使用时，需要用到适配器

*   *InputStreamReader* 可以把 *InputStream* 转换为 *Reader*
*   *OutputStreamWriter* 可以把 *OutputStream* 转换为 *Writer*

##### 标准I/O

Java 提供了 *System.in*、*System.out*、*System.err* 来定位标准 I/O，并提供了重定向的方法，I/O  重定向操作的是字节流，因此只能使用字节流装饰器装饰字符流



