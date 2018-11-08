## java.lang.string 的常用方法

**码点：是指与一个编码表中的某个字符对应的代码值。在 Unicode 标准中，码点采用十六进制书写，并加上前缀 U+。在 Java中，char 
类型描述了 UTF-16 编码中的一个代码单元。Java 字符串由 char 值序列组成。**
* char charAt(int index)    // 返回给定位置的代码单元。除非对底层的代码单元感兴趣，否则不需要调用这个方法
* int codePointAt(int index)    // 返回从给定位置开始的码点
* int offsetByCodePoints(int startIndex, int cpCount)           // 返回从 startIndex 代码点开始，位移 cpCount 后的码点索引
* int compareTo(String other)   // 按照字典排序，如果字符串位于 other 之前，返回一个负数；如果字符串位于 other 之后，返回一个正数；
如果两个字符串相等，返回 0
* IntStream codePoints().toArray();  // 将这个字符串的码点作为一个流返回。调用 toArray 将它们放在一个数组中
* new String(int[] codePoints, int offset, int count)   用数组中从 offset 开始的 count 个码点构造一个字符串
* Boolean equals(Object other)      // 如果字符串与 other 相等，返回 true
* Boolean equalsIgnoreCase(String other)        //  如果字符串与 other 相等（忽略大小写），返回 true
* boolean startsWith(String prefix)       // 如果字符串以 prefix 开头，返回 true
* boolean endsWith(String suffix)           // 如果字符串以 suffix 结尾，则返回 true
// 返回与字符串 str 或代码点 cp 匹配的第一个子串的开始位置。这个位置从索引 0 或 fromIndex 开始计算。如果在原始串中不存在 str，返回 -1
* int indexOf(String str)
* int indexOf(String str, int fromIndex)
* int indexOf(int cp)
* int indexOf(int cp, int fromIndex)
// 返回与字符串 str 或代码点 cp 匹配的最后一个子串的开始位置。这个位置从索引 0 或 fromIndex 开始计算。如果原始串中不存在 str，返回 -1
int lastIndexOf(String str)
int lastIndexOf(String str, int fromIndex)
int lastIndexOf(int cp)
int lastIndexOf(int cp, int fromIndex)
int length()        // 返回字符串的长度
int codePointCount(int startIndex, int endIndex)  // 返回 startIndex 和 endIndex -1 之间的代码点数量。没有配成对的代码代用字符将计入代码点
String replace(CharSequence oldString, CharSequence newString)  // 返回一个新字符串。这个字符串用 newString 代替原始字符串中所有的
oldString, 可以用 String 或 StringBuilder 对象作为 CharSequence 参数
// 返回一个新字符串，这个字符串包含原始字符串中从 beginIndex 到串尾或 endIndex -1 的所有代码单元
String substring(int beginIndex)
String substring(int beginIndex, int endIndex)
String toLowerCase()      // 返回一个新字符串，将原始字符串中的大写字母改为小写
String toUpperCase()        // 返回一个新的字符串。将字符串中的小写字母改为大写
String trim()               // 返回一个新字符串。这个字符串将删除了原始字符串头部和尾部的空格
String join(CharSequence delimiter, CharSequence... elements)       // 返回一个新字符串，用给定的定界符连接所有元素 
