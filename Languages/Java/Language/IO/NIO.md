### NIO

#### 概述

##### NIO 特性

在 1.4 版本之前，IO 类库是阻塞 IO；从 1.4 版本开始，引进了新的异步 IO 库，弥补了原来面向流的同步阻塞问题，提供了面向缓冲区的 IO

Java NIO 由三个核心组件组成

* Channel
* Buffer
* Selector

NIO 属于 IO 多路复用模型。

##### 与旧版 IO 区别

* 旧版 IO 是面向流的，NIO 是面向缓冲区的

  旧版 IO 是面向字节流或字符流的，在一般的 OIO 操作中，以流式的方式顺序地从一个流中读取一个或多个字节，不能随意改变读取指针的位置

  在 NIO 中引入了 Channel、Buffer，读取和写入，只需要从通道中读取数据到缓冲区，或将数据从缓冲区中写入到通道中。NIO 不像旧版 IO 是顺序操作，可以随意地读取 Buffer 中任意位置的数据

* 旧版 IO 的操作是阻塞的，而 NIO 的操作是非阻塞的

* OIO 没有 selector 该类

NIO 的实现，是基于底层的选择器的系统调用。NIO 的选择器，需要底层操作系统提供支持，而 OIO 不需要用选择器

##### Channel

在 OIO 中，同一个网络连接会关联到两个流：一个输入流，另一个输出流。通过这两个流，不断进行输入和输出的操作

在 NIO 中，同一个网络连接使用一个通道表示，所有的 NIO 的 IO  操作都是从通道开始的，一个通道类似于 OIO 中两个流的结合体，既可以从通道中读取，也可以向通道写入

一个通道可以表示为一个底层的文件描述符。对应不同的网络传输协议，在 Java 中都有不同的 NIO channel 通道实现

###### FileChannel

文件通道，用于文件的数据读写，为阻塞模式，不能设置为非阻塞模式

###### SocketChannel

套接字通道，用于 socket 套接字 TCP 连接的数据读写

###### ServerSocketChannel

服务器监听通道，允许监听 TCP 连接请求，为每个监听到的请求，创建一个 *SocketChannel* 套接字通道

###### DatagramChannel

数据报通 道，用于 UDP 协议的数据读写

##### Selector

是一个 IO 事件的查询器，通过选择器，一个线程可以查询多个通道的 IO 事件的就绪状态。实现 IO 多路复用，从具体的开发层面来说，首先把通道注册到选择器中，然后通过选择器内部的机制，可以查询这些注册的通道是否有已经就绪的 IO 事件

与 OIO 相比，使用选择器的最大优势：系统开销小，系统不必为每一个网络连接（文件描述符）创建进程/线程，从而大大减小了系统的开销

##### Buffer

NIO Buffer（NIO缓冲区）。通道的读取，就是将数据从通道读取到缓冲区中；通道的写入，就是将数据从缓冲区中写入到通道中。

本质上是一个内存块，既可以写入数据，也可以从中读取数据。是非线程安全的。是一个抽象类，内部是一个内存块数组，对应于 java 的主要数据类型，在 NIO 中有 8 种缓冲区类：*ByteBuffer*、*CharBuffer*、*DoubleBuffer*、*FloatBuffer*、*IntBuffer*、*LongBuffer*、*ShortBuffer*、*MappedByteBuffer*(专门用于内存映射的 ByteBuffer 类型)

Buffer 类在其内部，有一个 byte[] 数据内存块，作为内存缓冲区。为了记录读写的状态和位置，Buffer 类提供了一些重要的属性

不同的机器可能会使用不同的字节排序方法来存储数据。“big endian”（高位优先）将最重要的字节存放在地址最低的存储器单元。而“little endian”（低位优先）则是将最重要的字节放在地址最高的存储器单元。当存储量大于一个字节时，像int、float等，就要考虑字节的顺序问题了。ByteBuffer是以高位优先的形式存储数据的，并且数据在网上传送时也常常使用高位优先的形式。我们可以使用带有参数ByteOrder.BIG_ENDIAN或ByteOrder.LITTLE_ENDIAN的 order（）方法改变ByteBuffer的字节排序方式

###### capacity 属性

Buffer 类的 capacity 属性，表示内部容量的大小，一旦写入的对象数量超过了 capacity 容量，缓冲区就满了，不能再写

Buffer 类的 capacity 属性一旦初始化，就不能改变。（Buffer 类的对象在初始化时，会按照 capacity 分配内存）

<u>capacity 容量不是指内存块 byte[] 数组的字节的数量，指的是写入的数据对象的数量</u>

###### position 属性

Buffer 类的 position 属性，表示当前的位置。position 属性与缓冲区的读写模式有关。在不同的模式下，position 属性值是不同的。当缓冲区进行读写的模式改变时，position 会进行调整

* 在写入模式下，position 的值变化规则：

  1. 在刚进入到写模式时，position 值为 0，表示当前的写入位置从头开始

  2. 每当一个数据写到缓冲区之后，position 会向后移动到下一个可写的位置
  3. 初始的 position 值为 0，最大可写值 position 为 limit - 1，当 position 值达到 limit 时，缓冲区就已经无空间可写了

* 在读模式下，position 的值变化规则：

  1. 当缓冲区刚开始进入到读模式时，position 会被重置为 0
  2. 当从缓冲区读取时，也是从 position 位置开始读。读取数据后，position 向前移动到下一个可读的位置
  3. position 最大的值为最大可读上限 limit -1，当 position 达到 limit 时，表明缓冲区已经无数据可读

当新建一个缓冲区时，缓冲区处于写入模式，这时是可以写数据的。数据写入后，如果要从缓冲区读取数据，需要进行模式切换，调用 flip() 翻转方法，将缓冲区变成读取模式

在 flip 翻转过程种，position 由原来的写入位置，变成新的可读位置，就是 0，表示可以从头开始读，flip() 翻转的另外一半工作，要调整 limit 属性

###### limit 属性

Buffer 类的 limit 属性，表示读写的最大上限。limit 属性，也与缓冲区的读写模式有关。在不同的模式下，limit 的值含义是不同的

* 在写模式下，limit 属性值的含义为可写入的数据最大上限，在刚进入到写模式时，limit 的值会被设置成缓冲区的 capacity 容量值，表示可以一直将缓冲区的容量写满
* 在读模式下，limit 的值含义为最多能从缓冲区种读取到多少数据

###### mark 属性

调用 mark() 方法来设置 mark = position，再调用 reset() 可以让 position 恢复到 mark 标记的位置即 position = mark

###### 使用

使用 Java NIO Buffer 类的基本步骤：

1. 使用创建子类实例化对象的 allocate() 方法，创建一个 Buffer 类的实例对象

   在调用 allocate() 方法分配内存，返回实例对象后，缓冲区实例对象处于写模式，可以写入对象。

2. 调用 put 方法，将数据写道缓冲区中

   要写入缓冲区，需要调用 put() 方法。写入的数据类型要求与缓冲区的类型保持一致，使用 flip() 方法翻转模式

3. 写入完成后，在开始读数据前，调用 flip() 方法，将缓冲区转换为读模式

4. 调用 get() 方法，从缓冲区中读取数据

   调用 get() 方法，每次从 position 的位置读取一个数据，并且进行相应的缓冲区属性的调整。读取操作会改变可读位置position的值，而limit值不会改变。如果position值和limit的值相等，表示所有数据读取完成，position指向了一个没有数据的元素位置，已经不能再读了。此时再读，会抛出 BufferUnderflowException 异常。读取之后，必须调用 clear() 或 compact()，清空或压缩缓冲区，才能变成写入模式，让其重新可写。

   使用 rewind() 使缓冲区可以重复读

   mark() 方法的作用是将当前 position 的值保存起来，放在 mark 属性中，让 mark 属性记住这个临时位置；之后可以调用 Buffer.reset() 方法将 mark 的值恢复到 position 中

5. 读取完成后，调用 clean() 或 compace() 将缓冲区转换为写入模式

