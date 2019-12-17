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

