*java.lang.StringBuilder*
```java
// __StringBuffer 中修改缓冲区的方法是同步的，如果是多任务并发访问，就是用 StringBuffer, 因为这种情况下需要同步以防止 StringBuffer 崩溃，如果是单任务，则 StringBuilder 效率更好，其余基本一致__

StringBuilder builder = new StringBuilder();

// 构造一个空的字符串构建器

StringBuilder();

// 构建一个容量为 16 的空字符串构造器

StringBuilder(capacity:int);

// 构建一个指定容量的字符串构建器

StringBuilder(s:String);

// 构建一个指定字符串的字符串构建器

int length();

// 返回构建器或缓冲器中的代码单元数量

StringBuilder append(v: aPrimitiveType);

// 将一个基本类型值作为字符串追加到字符串构建器

StringBuilder append(String str);

// 追加一个代码单元并返回 `this`

StringBuilder append(char c);

// 追加一个代码单元并返回 `this`

StringBuilder append(data:char[]);

// 追加一个字符数组到字符串构建器

StringBuilder appendCodePoint(int cp);

// 追加一个代码点，并将其转换为一个或两个代码单元并返回 `this`

StringBuilder delete(startIndex: int, endIndex: int);

// 删除从 `startIndex` 到 `endIndex - 1` 的字符并返回 this

StringBuilder deleteCharAt(index: int);

// 删除给定索引位置的字符

void setCharAt(int i, char c);

// 将第 `i` 个代码单元设置为 `c`

StringBuilder insert(int offset, String str);

// 在 `offset` 位置插入一个字符串并返回 `this`

StringBuilder insert(int offset, Char c);

// 在 `offset` 位置插入入一个代码单元并返回 `this`

StringBuilder insert(index:int,data:char[],offset:int,len:int);

// 在字符串构建器的给定索引位置插入数组 `data` 的子数组

String toString();

// 返回一个与构建器或缓冲器内容相同的字符串

void trimToSize();

// 减少用于字符串构建器的存储大小

StringBuilder replace(startIndex: int, endIndex: int, s:String);

// 将该字符串构建器从 `startIndex` 到 `endIndex - 1` 的位置的字符替换为给定的字符串

StringBuilder reverse();

// 倒置构造器中的字符

void setCharAt(index: int, ch: char);

// 将该构造器的指定索引位置设为新的字符

int capacity();

// 返回该字符串构建器的容量

char charAt(index: int);

// 返回指定索引的字符

void setLength(newLength: int);

// 设置该构建器的新的长度

String substring(startIndex: int);

// 返回从 `startIndex` 开始的子字符串

String substring(startIndex: int,endIndex: int);

// 返回从 `startIndex` 到 `enIndex -1`的子字符串

```