### java.lang.string 的常用方法

**码点：是指与一个编码表中的某个字符对应的代码值。在 Unicode 标准中，码点采用十六进制书写，并加上前缀 U+。在 Java中，char 
类型描述了 UTF-16 编码中的一个代码单元。Java 字符串由 char 值序列组成。String 类型表示字符序列 String 是 java 的一个预定义类，String 不是基本类型，是引用类型。
字符串下标从 0 开始,字符串连接支持 + 号连接**
* `char charAt(int index)`    // 返回给定位置的代码单元。除非对底层的代码单元感兴趣，否则不需要调用这个方法
* `int codePointAt(int index)`    // 返回从给定位置开始的码点
* `int offsetByCodePoints(int startIndex, int cpCount)`           // 返回从 startIndex 代码点开始，位移 cpCount 后的码点索引
* `int compareTo(String other)`   // 按照字典排序，如果字符串位于 other 之前，返回一个负数；如果字符串位于 other 之后，返回一个正数,如果两个字符串相等，返回 0
* `int compareToIgnoreCase(String str)`         // compareTo 的忽略大小写版本
* `IntStream codePoints().toArray()`  // 将这个字符串的码点作为一个流返回。调用 toArray 将它们放在一个数组中
* `new String(int[] codePoints, int offset, int count)`   用数组中从 offset 开始的 count 个码点构造一个字符串
* `Boolean equals(Object other)`      // 如果字符串与 other 相等，返回 true
* `Boolean equalsIgnoreCase(String other)`        //  如果字符串与 other 相等（忽略大小写），返回 true
* `boolean startsWith(String prefix)`       // 如果字符串以 prefix 开头，返回 true
* `boolean endsWith(String suffix)`           // 如果字符串以 suffix 结尾，则返回 true
* `String concat(str)`                  // 将本字符串和字符串 str 连接，返回一个新字符串
// 返回与字符串 str 或代码点 cp 匹配的第一个子串的开始位置。这个位置从索引 0 或 fromIndex 开始计算。如果在原始串中不存在 str，返回 -1
* `int indexOf(String str)`
* `int indexOf(String str, int fromIndex)`
* `int indexOf(int cp)`
* `int indexOf(int cp, int fromIndex)`
// 返回与字符串 str 或代码点 cp 匹配的最后一个子串的开始位置。这个位置从索引 0 或 fromIndex 开始计算。如果原始串中不存在 str，返回 -1
* `int lastIndexOf(String str)`
* `int lastIndexOf(String str, int fromIndex)`
* `int lastIndexOf(int cp)`
* `int lastIndexOf(int cp, int fromIndex)`
* `int length()`        // 返回字符串的长度
* `int codePointCount(int startIndex, int endIndex)`  // 返回 startIndex 和 endIndex -1 之间的代码点数量。没有配成对的代码代用字符将计入代码点
* `String replace(CharSequence oldString, CharSequence newString)`  // 返回一个新字符串。这个字符串用 newString 代替原始字符串中所有的
oldString, 可以用 String 或 StringBuilder 对象作为 CharSequence 参数
// 返回一个新字符串，这个字符串包含原始字符串中从 beginIndex 到串尾或 endIndex -1 的所有代码单元
* `String substring(int beginIndex)`
* `String substring(int beginIndex, int endIndex)`
* `String toLowerCase()`      // 返回一个新字符串，将原始字符串中的大写字母改为小写
* `String toUpperCase()`        // 返回一个新的字符串。将字符串中的小写字母改为大写
* `String trim()`               // 返回一个新字符串。这个字符串将删除了原始字符串头部和尾部的空格
* `String join(CharSequence delimiter, CharSequence... elements)`       // 返回一个新字符串，用给定的定界符连接所有元素
* `boolean startsWith(String prefix, int toffset)`          // 测试从指定索引开始的此字符串的子字符串是否以指定的前缀开头
* `boolean startsWith(String prefix)`                       // 测试字符串是否以指定前缀开始
* `boolean endsWith(String suffix)`                         // 如果字符串以特定的后缀结束，返回 true
* `boolean contains(CharSequence s)`                        // 当且仅当此字符串包含指定 CharSequence 时，才返回true
* `substring(int beginIndex)`                               // 返回该字符串的子串，从特定位置 beginIndex 的字符开始到字符串的结尾
* `substring(int beginIndex, int endIndex)`            // 返回该字符串的子串，从特定位置 beginIndex 的字符开始到下标为 endIndex-1 的字符，
* `int indexOf(ch char)`                // 返回字符串中出现的第一个 ch 的下标，如果没有匹配，返回 -1
* `int indexOf(ch, fromIndex)`    // 返回字符串中 fromIndex 之后出现的第一个 ch 的下标，没有匹配返回 -1
* `int indexOf(String s)`        // 返回字符串中出现的第一个字符串的 s 的下标，如果没有匹配返回 -1
* `int indexOf(String s, int fromIndex)`  //  返回字符串中 fromIndex 之后出现的第一个字符串 s 的小标，如果没有匹配的，返回 - 1
* `int lastIndexOf(char ch)`            // 返回字符串中出现的最后一个ch的下标。如果没有匹配的，返回 -1
* `int lastIndexOf(ch, fromIndex)`      // 返回字符串中 fromIndex 之前出现的最后一个 ch 的下标。如果没有匹配的返回 -1
* `int lastIndexOf(string s)`           // 返回字符串中出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1
* `int lastIndexOf(String s, int fromIndex)`  // 返回字符串中 fromIndex 之前出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1

### 字符串和数字间的转换，常用格式化字符，与 c 的格式化字符类似
* 可以将数值型字符串转换为数值。要将字符串转换为 int 值，使用 Integer.parseInt 方法 `int intValue = Integer.parseInt(intString)`
* 要将字符串转为 double 值，使用 Double.parseDouble 方法 `double doubleValue = Double.parseDouble(doubleString)`
* 将数值转为字符串，只需要简单使用字符串的连接操作符 `String s = number + ""`;
* 常用的格式标识符：%b 布尔值，%c 字符，%d 十进制整数，%f 浮点数，%e 标准科学计数法形式的数，%s 字符串
指定宽度和精度
%5c 输出字符并在这个字符条目前面家 4 个空格
%6b 输出布尔值，在 false 值前加一个空格，在true 值前加两个空格
%5d 输出整数条目，宽度至少为 5，如果该条目的数字位数小于 5，就在数字前面加空格，如果该条目的数字位数大于5，则自动增加宽度
%10.2f 输出的浮点条目宽度至少为 10，包括小数点和小数点后两位数字。
%10.2e 输出的浮点条目的宽度至少为 10，包括小数点，小数点后两位数字
%12s   输出的字符串宽度至少为 12 个字符。如果该字符串条目小于 12 个字符，就在该字符串前加空格，如果该字符串条目多于 12 个字符，则自动增加宽度

### java.lang.StringBuilder
`StringBuilder builder = new StringBuilder();`
* `builder.append(ch);`         // 追加内容到字符串构建器 
* `StringBuilder()`           // 构造一个空的字符串构建器
* `int length()`              // 返回构建器或缓冲器中的代码单元数量
* `StringBuilder append(String str)`        // 追加一个代码单元并返回 this
* `StringBuilder appendCodePoint(int cp)`       // 追加一个代码点，并将其转换未一个或两个代码单元并返回 this 
* `void setCharAt(int i, char c)`               // 将第 i 个代码单元设置为 c
* `StringBuilder insert(int offset, String str)`        // 在 offset 位置插入一个字符串并返回 this
* `StringBuilder insert(int offset, Char c)`            // 在 offset 位置出入一个代码单元并返回 this
* `StringBuilder delete(int startIndex, int endIndex)`          // 删除偏移量从 startIndex 到 -endIndex -1 的代码单元并返回 this
* `String toString()`                       // 返回一个与构建器或缓冲器内容相同的字符串
