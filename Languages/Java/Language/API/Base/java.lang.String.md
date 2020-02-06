*java.lang.String*

```java
// 返回给定位置的代码单元。除非对底层的代码单元感兴趣，否则不需要调用这个方法
char charAt(int index);
// 返回从给定位置开始的码点
int codePointAt(int index);
// 返回从 startIndex 代码点开始，位移 cpCount 后的码点索引
int offsetByCodePoints(int startIndex, int cpCount);
// 按照字典排序，位于 other 之前，返回负数；如果位于 other 之后，返回一个正数,如果两个字符串相等，返回 0
int compareTo(String other);
// 忽略大小写比较
int compareToIgnoreCase(String other);
// 将这个字符串的码点作为一个流返回，调用 toArray 将它们放在一个数组中
IntStream codePoints();
// 用数组中 offset 开始的 count 个码点构成的一个字符串
new String(int[] codePoints, int offset, int count);
// 如果字符串与 other 相等，返回 true
boolean equals(Object other);
// 如果字符串与 other 相等（忽略大小写），返回 true
boolean equalsIgnoreCase(String other);
// 如果字符串以 prefix 开头，返回 true
boolean startsWith(String prefix);
// 如果字符串以 suffix 结尾，则返回 true
boolean endsWith(String suffix);
// 将本字符串和字符串 str 连接，返回一个新字符串
String concat(str);
// 返回字符串的长度
int length();
// 返回 startIndex 和 endIndex -1 之间的代码点数量。没有配成对的代码代用字符将计入代码点
int codePointCount(int startIndex, int endIndex);
// 返回一个新字符串。这个字符串用 newString 代替原始字符串中所有的 oldString
String replace(CharSequence oldString, CharSequence newString);
// 返回一个新字符串，将原始字符串中的大写字母改为小写
String toLowerCase();
// 返回一个新的字符串。将字符串中的小写字母改为大写
String toUpperCase();
// 返回一个新字符串。这个字符串将删除了原始字符串头部和尾部的空格
String trim();
// 返回一个新字符串，用给定的定界符连接所有元素
String join(CharSequence delimiter, CharSequence... elements);
// 测试从指定索引开始的此字符串的子字符串是否以指定的前缀开头
boolean startsWith(String prefix, int toffset);
// 此字符串包含指定 CharSequence 时，返回 true
boolean contains(CharSequence s);
// 返回该字符串的子串，从特定位置 beginIndex 的字符开始到字符串的结尾
String substring(int beginIndex);
// 返回该字符串的子串，从特定位置 beginIndex 的字符开始到下标为 endIndex-1 的字符
String substring(int beginIndex, int endIndex);
// 返回字符串中出现的第一个 ch 的下标，如果没有匹配，返回 -1
int indexOf(char ch);
// 返回字符串中 fromIndex 之后出现的第一个 ch 的下标，没有匹配返回 -1
int indexOf(char ch, int fromIndex);
// 返回字符串中出现的第一个字符串 s 的下标，如果没有匹配返回 -1
int indexOf(String s);
// 返回字符串中 fromIndex 之后出现的第一个字符串 s 的小标，如果没有匹配的，返回 - 1
int indexOf(String s, int fromIndex);
// 返回字符串中出现的最后一个 ch 的下标。如果没有匹配的，返回 -1
int lastIndexOf(char ch);
// 返回字符串中 fromIndex 之前出现的最后一个 ch 的下标。如果没有匹配的返回 -1
int lastIndexOf(char ch, int fromIndex);
// 返回字符串中出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1
int lastIndexOf(String s);
// 返回字符串中 fromIndex 之前出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1
int lastIndexOf(String s, int fromIndex);
// 指定分隔符分割字符串，返回一个字符串数组
String[] split(String regex);
// 正则匹配
boolean matches(String regex);
// 正则表达式全局替换
String replaceAll(String regex, String replacement);
// 正则表达式首次出现替换
String replaceFirst(String regex, String replacement);
// 将下标从 scrbegin 到 srcEnd -1 的字串赋值到字符数组 dst 中下标从 detBegin 开始的位置
void getChars(int srcBegin, int srcEnd, char dst[], int dstBegin);
String valueOf(<T> t);
// 返回格式化字符串
static String format(String format, Object ...objects);
static String format(Locale l, String format, Object ...objects);
```