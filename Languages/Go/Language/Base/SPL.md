### SPL

#### 内置函数

* close

  管道通信

* len

  返回某个类型的长度或数量（字符串、数组、切片、map、管道）

* cap

  返回某个类型的最大容量（只能用于切片和 map）

* new

  分配内存，用于值类型和用户定义的类型，如自定义结构。`new(type)` 分配类型 T 的零值并返回其地址，也可以被用于基本类型 `v := new(int)`

* make

  分配内存，`make(T)` 返回类型 T 的初始化之后的值，它比 new 进行更多的工作

* copy

  复制切片

* append

  连接切片

* panic

  错误处理

* recover

  错误处理

* print

  底层打印，在部署环境中建议使用 `fmt` 包

* println

  底层打印

* complex

  创建复数

* realimag

  操作复数

#### strings

https://golang.org/pkg/strings/

```go
// 判断字符串 s 是否以 prefix 开头
strings.HasPrefix(s, prefix string) bool
// 判断字符串 s 是否以 suffix 结尾
strings.HasSuffix(s, suffix string) bool
// 判断字符串 s 是否包含 substr
strings.Contains(s, substr string) bool
// Index 返回字符串 str 在字符串 s 中的索引（str 的第一个字符的索引），-1 表示字符串 s 不包含 str
strings.Index(s, str string) int
// LastIndex 返回字符串 str 在字符串 s 中最后出现位置的索引（str 的第一个字符索引），-1 表示不包含
strings.LastIndex(s, str string) int
// 如果 ch 是非 ASCII 字符，使用以下函数对字符进行定位
strings.IndexRune(s string, r rune) int
// 将 str 中的前 n 个字符串 old 替换为字符串 new，并返回一个新的字符串，如果 n=-1则替换所有 old为 new
strings.Replace(str, old, new, n) string
// 用于计算字符 str 在字符串 s 中出现的非重叠次数
strings.Count(s, str string) int
// 重复 count 次字符串 s 并返回一个新的字符串
strings.Repeat(s, count int) string
// 将字符串中的 Unicode 字符全部转换为相应的小写字符
strings.ToLower(s) string
// 将字符串中的 Unicode 字符全部转换为相应的大写字符
strings.ToUpper(s) string
// 剔除字符串开头和结尾的空白符号或指定字符
strings.TrimSpace(s, str string) string
// 剔除左边字符
strings.TrimLeft(s, str string) string
// 剔除右边字符
strings.TrimRight(s, str string) string
// 1 个或多个空白符号来作为动态长度的分隔符将字符串分割成若干小块，并返回一个 slice，如果字符串只包含空白符号，则返回一个长度为 0 的 slice
strings.Fields(s) slice
// 使用自定义分割符号来对指定字符串进行分割，返回 slice
strings.Split(s, sep)
// 将元素类型为 string 的 slice 使用分割符号来拼接组成一个字符串
strings.Join(sl []string, sep string) string
// 生成一个 Reader 并读取字符串中的内容，返回指向该 Reader 的指针
strings.NewReader(str) *Reader
// 从 []byte 中读取内容
Read()
// 从字符串中读取下一个 byte 或 rune
ReadByte()
ReadRune()
```

#### strconv

https://golang.org/pkg/strconv/

与字符串相关的类型转换，该包包含了一些变量用于获取程序运行的操作系统平台下 int 类型所占的位数

```go
// 操作系统平台下 int 类型所占位数
strconv.IntSize
// 数字转字符串,返回数字 i 所表示的字符串类型的十进制数
strconv.Itoa(i int) string
// 将 64 位浮点数的数字转换为字符串，fmt 表示格式（其值可以是 b,e,f,g) prec 表精度，bitsize 使用 32 表示 float32，64 表示 float64
strconv.FormatFloat(f float64, fmt byte, prec int, bitSize int) string
// 将字符串转换为 int 型
strconv.Atoi(s string)(i int, err error)
// 将字符串转换为 float64 型
strconv.ParseFloat(s string, bitSize int) (f float64, err error)
```

#### time

https://golang.org/pkg/time/

time 包提供了一个数据类型 `time.Time` (作为值使用)以及显示和测量时间和日期的功能函数；`Duration` 类型表示两个连续时刻所相差的纳秒数，类型为 `int64`，`Location` 类型映射某个时区的时间，`UTC` 表示通用协调世界时间

```go
// 当前时间
time.Now()
// 获取时间的一部分
t.Day()
t.Minute()
// 计算函数运行时间
start := time.Now()
longCalculation()
end := time.Now()
delta := end.Sub(start)
```

#### runtime

#### log

#### regexp

```go
// 简单匹配
ok, _ := regexp.Match(pat, []byte(searchIn))
ok, _ := regexp.MatchString(pat, searchIn)
// 结构
re, _ := regexp.Compile(pat)
str := re.ReplaceAllString(searchIn, "###")
str2 := re.ReplaceAllStringFunc(searchIn, f)
```

#### sync

`sync.Mutex` 是一个互斥锁，它的作用是守护在临界区入口 来确保同一时间只能有一个线程进入临界区

`sync.RWMutex` 锁，能通过 `RLock()` 来允许同一时间多个线程对变量进行读操作，但只能一个线程进行写操作。

#### big

* `big.Int`

  ```go
  // 大整数构造
  big.NewInt(n)
  // 大有理数构造, N 分子，D 分母都是 int64 型整数
  big.NewRat(N,D)
  ```

* `big.Rat`

Go 语言不支持运算符重载，所有大数字类型都有像 `Add()` 和 `Mul()` 这样的方法

#### runtime

```go
// 触发 GC
runtime.GC()
// 当前内存分配
var m runtime.MemStats
runtime.ReadMemStats(&m)
fmt.Printf("%d kb\n", m.Alloc / 1024)
// 对象被内存移除前执行一些特殊操作,func(obj *typeObj) 需要一个 typeObj 类型的指针参数 obj，特殊操作会在它上面执行。func 也可以是一个匿名函数，在对象被 GC 进程选中并从内存中移除以前，SetFinalizer 都不会执行，即使程序正常结束或发生错误
runtime.SetFinalizer(Obj, func(obj *typeObj))
```

#### reflect

反射是通过检查一个接口的值，变量首先被转换成空接口，接口的值包含一个 type 和 value。反射可以从接口值反射到对象，也可以从对象反射回接口值

```go
// 返回被检查对象的类型和值
reflect.TypeOf
reflect.ValueOf
// 使用反射设置值
var x float64 = 3.4
v := reflect.ValueOf(x)
// setting a value:
// v.SetFloat(3.1415) // Error: will panic: reflect.Value.SetFloat using unaddressable value
fmt.Println("settability of v:", v.CanSet())
v = reflect.ValueOf(&x) // Note: take the address of x.
fmt.Println("type of v:", v.Type())
fmt.Println("settability of v:", v.CanSet())
v = v.Elem()
fmt.Println("The Elem of v is: ", v)
fmt.Println("settability of v:", v.CanSet())
v.SetFloat(3.1415) // this works!
fmt.Println(v.Interface())
fmt.Println(v)
```

#### fmt

```go
// 接收标准输入,将空格分隔的值依次存放到后续的参数内，直到碰到换行
fmt.Scanln(&firstName, &lastName)
```

#### bufio

```go
// 创建读取器，并将其与标准输入绑定,读取器对象提供 ReadString(delim byte)，该方法从输入中读取内容，直到碰到 delim 指定的字符，然后将读取到的内容连同 delim 字符一起放到缓冲区，出错返回 nil，读到文件结束则返回读取到的字符串和 io.EOF, 如果读取过程中没有碰到 delim 字符，将返回错误 err != nil
inputReader := bufio.NewReader(os.Stdin)
// 读文件 inputFile 是 *os.File 类型的。该类型是一个结构，表示一个打开文件的描述符（文件句柄）
inputFile, inputError := os.Open("inputFileName")
if inputError != nil {
    return
}
defer inputFile.Close()
inputReader := bufio.NewReader(inputFile)
for {
    inputString, readerError := inputReader.ReadString('\n')
    fmt.Printf("The input was: %s", inputString)
    if readerError == io.EOF {
        return
    }
}
```

#### ioutil

```go
// 将整个文件的内容读到一个字符串里
buf, err := ioutil.ReadFile(inputFile)
if err != nil {
    fmt.Fprintf(os.Stderr, "File Error: %s\n", err)
}
fmt.Printf("%s\n", string(buf))
    err = ioutil.WriteFile(outputFile, buf, 0644) // oct, not hex
    if err != nil {
        panic(err.Error())
}
// 带缓冲的读取, n 为读到的字节数
buf := make([]byte, 1024)
n, err := inputReader.Read(buf)
if (n == 0) { break }
```

#### compress

读取压缩文件的功能，支持：bzip2，flate，gzip，lzw，zlib

```go
// 读取 gzip 文件
fName := "MyFile.gz"
var r *bufio.Reader
fi, err := os.Open(fName)
if err != nil {
    fmt.Fprintf(os.Stderr, "%v, Can't open %s: error: %s\n", os.Args[0], fName, err)
    os.Exit(1)
}
fz, err := gzip.NewReader(fi)
if err != nil {
    r = bufio.NewReader(fi)
} else {
    r = bufio.NewReader(fz)
}
for {
    line, err := r.ReadString('\n')
    if err != nil {
        fmt.Println("Done reading file")
        os.Exit(0)
    }
    fmt.Println(line)
}
```

#### json

```go
type Address struct {
    Type    string
    City    string
    Country string
}

type VCard struct {
    FirstName string
    LastName  string
    Addresses []*Address
    Remark    string
}

func main() {
    pa := &Address{"private", "Aartselaar", "Belgium"}
    wa := &Address{"work", "Boom", "Belgium"}
    vc := VCard{"Jan", "Kersschot", []*Address{pa, wa}, "none"}
    // fmt.Printf("%v: \n", vc) // {Jan Kersschot [0x126d2b80 0x126d2be0] none}:
    // JSON format:
    js, _ := json.Marshal(vc)
    fmt.Printf("JSON format: %s", js)
    // using an encoder:
    file, _ := os.OpenFile("vcard.json", os.O_CREATE|os.O_WRONLY, 0666)
    defer file.Close()
    enc := json.NewEncoder(file)
    err := enc.Encode(vc)
    if err != nil {
        log.Println("Error in encoding json")
    }
}
```

出于安全考虑，在 web 应用中最好使用 `json.MarshalforHTML()` 函数，其对数据执行 HTML 转码，文本可以被安全地嵌在 HTML `<script>` 标签中

JSON 与 Go 类型对应如下：

* bool 对应 JSON 的 booleans
* float64 对应 JSON 的 numbers
* string 对应 JSON 的 string
* nil 对应 JSON 的 null

不是所有的数据都可以编码为 JSON 类型：只有验证通过的数据结构才能被编码：

* JSON 对象只支持字符串类型的 key；要编码一个 Go map 类型，map 必须是 `[string] T`
* Channel，复杂类型和函数类型不能被编码
* 不支持循环数据结构；它将引起序列化进入一个无限循环
* 指针可以被编码，实际上是对指针指向的值进行编码

json 包提供了 `Decoder` 和 `Encoder` 类型来支持常用 JSON 数据流读写

```go
// NewDecoder 和 NewEncoder 分别封装了 io.Reader 和 io.Writer 接口
func NewDecoder(r io.Reader) *Decoder
func NewEncoder(w io.Writer) *Encoder
```

#### xml

```go
var t, token xml.Token
var err error

func main() {
    input := "<Person><FirstName>Laura</FirstName><LastName>Lynn</LastName></Person>"
    inputReader := strings.NewReader(input)
    p := xml.NewDecoder(inputReader)

    for t, err = p.Token(); err == nil; t, err = p.Token() {
        switch token := t.(type) {
        case xml.StartElement:
            name := token.Name.Local
            fmt.Printf("Token name: %s\n", name)
            for _, attr := range token.Attr {
                attrName := attr.Name.Local
                attrValue := attr.Value
                fmt.Printf("An attribute is: %s %s\n", attrName, attrValue)
                // ...
            }
        case xml.EndElement:
            fmt.Println("End of token")
        case xml.CharData:
            content := string([]byte(token))
            fmt.Printf("This is the content: %v\n", content)
            // ...
        default:
            // ...
        }
    }
}
```

包中定义了若干 XML 标签类型：`StartElement`，`Chardata`（从开始标签到结束标签之间得实际文本），`EndElement`，`Comment`，`Directive`，`Proclnst`

包中同样定义了一个结构解析器：`NewParser` 方法持有一个 io.Reader（这里具体类型是 strings.NewReader）并生成一个解析器类型的对象。还有一个 `Token()` 方法返回输入流里的下一个 XML token。在输入流的结尾处，会返回（nil，io.EOF）

XML 文本被循环处理直到 `Token()` 返回一个错误，因为已经到达文件尾部，再没有内容可供处理了。通过一个 type-switch 可以根据一些 XML 标签进一步处理。Chardata 中的内容只是一个 [] byte，通过字符串转换让其变得可读性强一些。



