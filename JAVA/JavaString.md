## Java 字符串 API 及用法

### java.lang.String API

* `char charAt(int index)` : 返回给定位置的代码单元。除非对底层的代码单元感兴趣，否则不需要调用这个方法

* `int codePointAt(int index)`: 返回从给定位置开始的码点

* `int offsetByCodePoints(int startIndex, int cpCount)`: 返回从 `startIndex` 代码点开始，位移 `cpCount` 后的码点索引

* `int compareTo(String other)`: 按照字典顺序，如果字符串位于 `other` 之前，返回一个负数; 如果字符串位于 `other` 之后，返回
一个正数；如果两个字符串相等，返回 0。

* `IntStream codePoints()`: 将这个字符串的码点作为一个流返回。调用 `toArray` 可将它们放在一个数组中

* `new String(int[] codePoints, int offset, int count)`: 用数组中从 `offset` 开始的 `count` 个码点构造一个字符串

* `boolean equals(Object other)`: 如果字符串与 `other` 相等，返回 `true`;

* `boolean equalsIgnoreCase(String other)`: 如果字符串与 `other` 相等（忽略大小写），返回 `true`

* `boolean startsWith(String prefix)`: 如果字符串以 `prefix` 开头，返回 `true`

* `boolean endsWith(String suffix)`: 如果字符串以 `suffix` 结尾，返回 `true`

* `int indexOf(String str)`、`int indexOf(String str, int fromIndex)`、`int indexOf(int cp)`、`int indexOf(int cp, int fromIndex)` 
返回与字符串 `str` 或代码点 `cp` 匹配的第一个子串的凯斯位置。这个位置从索引 0 或 `fromIndex` 开始计数。如果在原始串中不存在 `str`,返回 -1

* `int lastIndexOf(String str)`、`int lastIndexOf(String str, int fromIndex)`、`int lastIndexOf(int cp)`、`int lastIndexOf(int cp, int fromIndex)`
返回与字符串 `str` 或代码点 `cp` 匹配的最后一个子串的开始位置。这个位置从原始串尾端或 `fromIndex` 开始计算

* `int length()`: 返回字符串的长度

* `int codePointCount(int startIndex, int endIndex)`: 返回 `startIndex` 和 `endIndex -1` 之间的代码点数量。没有配成对的代用字符将计入代码点

* `String replace(CharSequence oldString, charSequence newString)`: 返回一个新字符串。这个字符串用 `newString` 代替原始字符串中所有的 `oldString`。可以用
 `String` 或 `StringBuilder` 对象作为 `CharSequence` 参数
 
* `String substring(int beginIndex)`、`String substring(int beginIndex, int endIndex)`: 返回一个新字符串。这个字符串包含原始字符串
中从 `beginIndex` 到串尾部或 `endIndex -1` 的所有代码单元

* `String toLowerCase()`: 返回一个新字符串。这个字符串将原始字符串中的大写字母改为小写。

* `String toUpperCase()`: 返回一个新字符串。这个字符串将原始字符串中的小写字母改为大写。

* `String trim`: 返回一个新字符串。这个字符串将删除原始字符串头和尾的空格

* `String join(CharSequence delimiter, CharSequence... element)`: 返回一个新字符串，用给定的定界符连接所有元素

`CharSequence` 类型的参数，是一种接口类型，所有字符串都属于这个接口。

### java.lang.StringBuilder API

* `StringBuilder()`: 构造一个空的字符串构建器

* `int length()`: 返回构建器或缓冲器中的代码单元数量

* `StringBuilder append(String str)`: 追加一个字符串并返回 this

* `StringBuilder append(char c)`: 追加一个代码单元并返回 this

* `StringBuilder appendCodePoint(int cp)`: 追加一个代码点，并将其转换为一个或两个代码单元并返回 `this`

* `void setCharAt(int i, char c)`: 将第 i 个代码单元设置为 c

* `StringBuilder insert(int offset, String str)`: 将 `offset` 位置插入一个字符串并返回 this

* `StringBuilder insert(int offset, Char c)`: 在 `offset` 位置插入一个代码单元并返回 this

* `StringBuilder delete(int startIndex, int endIndex)`: 删除偏移量从 `startIndex` 到 `endIndex -1` 的代码单元并返回 this

* `String toString()`: 返回一个与构建器或缓冲器内容相同的字符串