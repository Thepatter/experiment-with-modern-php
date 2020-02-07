*java.util.Scanner*
```java
Scanner (InputStram in);

// 用给定的输入流创建一个 `Scanner` 对象

Scanner (File f);

// 构造一个从给定文件读取数据的 `Scanner`

Scanner (String data);

// 构造一个从给定字符串读取数据的 `Scanner`

void close();

// 关闭该 Scanner

boolean hasNext();

// 如果该 `Scanner` 还有更多数据，则返回 true

boolean hasNextInt();

boolean hasNextDouble();

// 检测是否还有表示整数或浮点数的下一个字符序列

String nextLine();

// 读取输入的下一行内容

String next();

// 读取输入的下一个单词（以空格作为分隔符）

int nextInt();

// 读取输入的一个 int

- `double nextDouble`
// 读取下一个 double

byte nextByte();

// 从该 `Scanner` 中读取下一个标记作为 `byte` 值返回

short nextShort();

long nextLong();

useDelimiter(pattern: String);

// 设置 `Scanner` 的分隔符，并且返回该 `scanner`

```