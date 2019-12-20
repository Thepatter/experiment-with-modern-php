### Go base usage

#### 垃圾回收

Go 开发者不需要写代码来释放程序中不再使用的变量和结构占用的内存，在 Go 运行时中有一个独立的进程：GC，它搜索不再使用的变量然后释放它们的内存，可以通过 `runtime` 包访问 GC 进程

通过调用 `runtime.GC()` 函数可以显式的触发 GC，但这只在某些罕见的场景下才有用，当内存资源不足时调用 `runtime.GC()` ，它会在此函数执行的点上立即释放一大片内存，此时程序可能会有短时的性能下降（GC 执行）

#### 读写数据

##### 文件读写

文件使用指向 `os.File` 类型的指针来表示的（句柄）

```go
// 写文件
outputFile, outputError := os.OpenFile("output.dat", os.O_WRONLY | os.O_CREATE, 0666)
if outputError != nil {
    fmt.Printf("An error occurred with file opening or creation\n")
    return
}
defer outputFile.Close()
outputWriter := bufio.NewWriter(outputFile) // 写入器
outputString := "hello world\n"
outputWriter.WriteString(ouputString)
outputWriter.Flush()
// 简单写入,直接将内容写入文件
fmt.Fprintf(outputFile, "some data.\n")
// 直接写入
os.Stdout.WriteString("hello, world\n")
f, _ := os.OpenFile("test", os.O_CREATE|os.O_WRONLY, 0)
defer f.Close()
// 不用缓冲直接写
f.WriteString("helo, world in a file\n")
```

`OpenFile` 函数有三个参数：文件名、一个或多个标志（`os.O_RDONLY`：只读，`os.O_WRONLY`：只写，`os.O_APPEND`：追加写，`os.O_CREATE`：创建，指定文件不存在则创建，`os.O_TRUNC`：截断，文件存在，将该文件长度截为0，使用逻辑运算符 `|` 连接）

在读文件的时候，文件的权限是被忽略的，所以在使用 `OpenFile` 时传入的第三个参数可以用 0，在写文件时，不管是 Unix 还是 Windows，都需要使用 0666

`fmt` 包里的 F 开头的 Print 函数可以直接写入任何 `io.Writer` ，包括文件

##### 文件拷贝

```go
func CopyFile(dstName, srcName string) (written int64, err error) {
    src, err := os.Open(srcName)
    if err != nil {
        return
    }
    defer src.Close()
    dst, err := os.OpenFile(dstName, os.O_WRONLY | os.O_CREATE, 0644)
    if err != nil {
        return
    }
    defer dst.Close
    return io.Copy(dst, src)
}
```

##### 标准输入

`os` 包中有一个 string 类型的切片变量 `os.Args`，用来处理一些基本的命令行参数

```go
if len(os.Args) > 1 {
    who += strings.Join(os.Args[1:], " ")
}
```

```go
// echo server
var NewLine = flag.Bool("n", false, "print newLine")
const (
	Space = " "
    Newline = "\n"
)
func main() {
    flag.PrintDefaults()
    flag.Parse()
    var s string = ""
    for i := 0; i < flag.NArg(); i++ {
        if i > 0 {
            s += " "
            if *NewLine {
                s += NewLine
            }
        }
        s += flag.Arg(i)
    }
    os.Stdout.WriteString(s)
}
```

参数。`Parse()` 之后 `flag.Arg(i)` 全部可用，`flag.Arg(0)` 就是第一个真实的 flag，而不是像 `os.Args(0)` 放置程序的名字。

`flag.Narg()` 返回参数的数量。解析后 flag 或常量就可用了。
`flag.Bool()` 定义了一个默认值是 `false` 的 flag：当在命令行出现了第一个参数（这里是 "n"），flag 被设置成 `true`（NewLine 是 `*bool` 类型）。flag 被解引用到 `*NewLine`，所以当值是 `true` 时将添加一个 newline（"\n"）。

##### Gob 传输数据

Gob 是 Go 自己得以二进制形式序列化和反序列化程序数据得格式；可以在 `encoding` 包中找到。这种格式得数据简称为 `Gob`，通常用于远程方法调用参数和结果得传输，以及应用程序和机器之间得数据传输。Gob 特定地用于纯 Go 得环境中（两个 Go 服务之间得通信）

Gob 不是可外部定义，语言无关得编码方式，首选格式是二进制，并不是一种不同于 Go 得语言，而是在编码和解码过程中用到了 Go 的反射。Gob 文件或流是完全自描述的：里面包含的所有类型都有一个对应的描述，并且总是可以用 Go 解码，而不需要了解文件的内容

只有可导出的字段会被编码，零值会被忽略。在解码结构体的时候，只有同时匹配名称和可兼容类型的字段才会被解码。当源数据类型增加新字段后，Gob 解码客户端仍然可以以这种方法正常工作。解码客户端会继续识别以前存在的字段

和 JSON 的使用方式一样，Gob 使用通用的 `io.Writer` 接口，通过 `NewEncoder()` 函数创建 `Encoder` 对象并调用 `Encode()`；相反的过程使用通用的 `io.Reader` 接口，通过 `NewDecoder()` 函数创建 `Decoder` 对象并调用 `Decode`。

#### Hash

##### hash

实现了 `adler32`，`crc32`，`crc64`，`fnv` 校验

##### crypto

实现了 hash 包未提供的 has算法和加密算法

#### 错误处理

Go 有一个预定义的 error 接口类型，错误值用来表示异常状态

```go
type error interface {
    Error() string
}
```

任何时候当需要一个新的错误类型，都可以用 `errors` 包的 `errors.New` 函数接收合适的错误信息来创建

当发生像数组下标越界或类型断言失败这样的运行错误时，Go 运行时会触发运行时 panic，伴随着程序的崩溃抛出一个 `runtime.Error` 接口类型的值。这个错误值有个 `RuntimeError()` 方法用于区别普通错误。`panic` 可以直接从代码初始化：当错误条件很严苛且不可恢复，程序不能继续运行时，可以使用 `panic` 函数产生一个中止程序的运行时错误。`panic` 接收一个做任意类型的参数，通常是字符串，在程序死亡时被打印出来。Go 运行时负责中止程序并给出调试信息

```go
panic("A severe error occurred: stopping the program!")
```

在多层嵌套的函数调用中调用 panic，可以马上中止当前函数的执行，所有的 defer 语句都会保证执行并把控制权交还给接收到 panic 的函数调用者。这样向上冒泡直到最顶层，并执行（每层）defer，在栈顶处程序崩溃，并在命令行中用传给 panic 的值报告错误情况，这个终止过程就是 panicking。

标准库中有许多包含 `Must` 前缀的函数，像 `regexp.MustComplie` 和  `template.Must`；当正则表达式或模板中传入的转换字符串导致错误时，这些函数会 panic

不能随意用 panic 中止程序，必须尽力补救错误让程序能继续执行

`recover` 内建函数被用于从 panic 或错误场景中恢复；让程序可以从 panicking 重新获得控制权，停止终止过程恢复正常执行。`recover` 只能在 `defer` 修饰的函数中使用，用于取得 panic 调用中传递过来的错误值，如果是正常执行，调用 `recover` 会返回 nil，且没有其他效果。panic 会导致栈被展开直到 defer 修饰的 `recover()` 被调用或程序中止

* 在包内部，总是应该从 panic 中 recover：不允许显式的超出包范围的 `panic()`
* 向包的调用者返回错误值，而不是 panic

在包内部，特别是在非导出函数中有很深层次的嵌套调用时，对主调函数来说用 panic 来表示应该被翻译成错误的错误场景是很有用的（并且提高了代码可读性）

#### 协程与通道

##### Go 协程

在 Go 中，应用程序并发处理的部分被称作 `goroutines(协程)`，它可以进行更有效的并发运算，在协程和操作系统线程之间并无一对一的关系，协程是根据一个或多个线程的可用性，映射在线程之上；协程调度器在 Go 运行时很好的完成了这个工作

协程工作在相同的地址空间中，所以共享内存的方式一定是同步的；这个可以使用 sync 包来实现，不过 go 中不鼓励这样，Go 使用 `channels` 来同步协程

当系统调用阻塞协程时，其他协程会继续在其他线程上工作。协程的设计隐藏了许多线程创建和管理方面的复杂工作。协程是轻量的，比线程更轻，使用少量的内存和资源，创建非常廉价，并且它们对栈进行分割，从而动态的增加（或缩减）内存的使用；栈的管理是自动的，但不是由垃圾回收器管理的，而是在协程退出后自动释放

协程可以运行在多个操作系统线程之间，也可以运行在线程之内。存在两种并发方式：确定性的（明确定义排序）和非确定性的（加锁/互斥从而未定义排序）。Go 的协程和通道支持确定性的并发方式

协程是通过关键字 `go` 调用一个函数或方法来实现的（也可以是匿名或 lambda 函数），这样会在当前的计算过程中开始一个同时进行的函数，在相同的地址空间中并且分配了独立的栈

协程的栈会根据需要进行伸缩，不会出现栈溢出；无需关心栈的大小。当协程结束的时候，它会静默退出，用来启动这个协程的函数也不会得到任何的返回值

任何 Go 程序都必须有的 `main()` 函数也可以看做是一个协程，尽管它并没有通过 `go` 来启动，协程可以在程序初始化的过程中运行（在 `init()` 函数中）

在一个协程中，比如它需要进行非常密集的运算，可以在循环中周期的使用 `runtime.Gosched()`：这会让出处理器，允许运行其他协程，它并不会使当前协程挂起，会自动恢复执行，可以使计算均匀分布，使通信不至于迟迟得不到响应

在 `gc` 编译器下，必须设置 `GOMAXPROCS` 为一个大于默认值 1 的数值来允许运行时支持使用多于 1 个的操作系统线程，否则所有的协程都会共享一个线程。当 `GOMAXPROCS` 大于 1 时，会有一个线程池管理众多线程。`gccgo` 编译器会使 `GOMAXPROCS` 与运行中的协程数量相等。假设一个机器上有 `n` 个处理器核心，如果设置换行变量 `GOMAXPROCS>=n`，或者执行 `runtime.GOMAXPROCS(n)`，那么协程会被分割到 `n` 个处理器上。如果有 `n` 个核心，会设置 `GOMAXPROCS` 为 `n-1` 以获得最佳性能，但同样也需要保证，`协程的数量 > 1 + GOMAXPROCS > 1`。

如果在某一时间只有一个协程在执行，不要设置 `GOMAXPROCS`，`GOMAXPROCS` 等同于（并发的）线程数量，在一台核心数多于 1 个的机器上，会尽可能有等同于核心数的线程在并行运行

当 `main()` 函数返回的时候，程序退出，它不会等待任何其他非 `main` 协程的结束。在服务器程序中，每个请求都会启动一个协程来处理，协程时独立的处理单元，一旦陆续启动一些协程，无法确定它们时什么时候真正开始执行的，代码逻辑必须独立于协程调用的顺序

Go 与其他程序协程区别：

* Go 协程意味着并行（或者可以以并行的方式部署），协程一般来说不是这样的
* Go 协程通过通道来通信；协程通过让出和恢复操作来通信

Go 协程比其他程序协程更强大，也更容易从协程的逻辑复用到 Go 协程

##### 协程间的信道

Go 有一个特殊的类型：`channel`，可以通过它们发送类型化的数据在协程之间通信，可以避开所有内存共享导致的问题，通道的通信方式保证了同步性，数据通过通道，同一时间只有一个协程可以访问数据，不会出现数据竞争。

```go
// 声明通道，未初始化的通道的值是 nil
var identifier chan datatype
// 分配内存
var ch1 chan string
ch1 = make(chan string) // 等价于 ch1 := make(chan string)
```

通道只能传输一种类型的数据，所有类型都可用于通道。通道实际上是类型化消息的队列：使数据得以传输。是先进先出的结构可以保证发送给它们的元素的顺序。通道也是引用类型，可以存储在变量中，作为函数的参数传递，从函数返回以及通过通道发送它们自身。它们是类型化的，允许类型检查。

```go
// 流行通道 发送
ch <- int1 // 同通道 ch 发送变量 int1
// 从通道流出，接收
int2 = <- ch // 变量 int2 从通道 ch接收数据
// 未声明 int2
int2 := <- ch
```

