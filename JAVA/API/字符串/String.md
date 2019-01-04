## java.lang.string

**码点：是指与一个编码表中的某个字符对应的代码值。在 Unicode 标准中，码点采用十六进制书写，并加上前缀 U+。在 Java中，char 
类型描述了 UTF-16 编码中的一个代码单元。Java 字符串由 char 值序列组成。String 类型表示字符序列 String 是 java 的一个预定义类，String 不是基本类型，是引用类型。
字符串下标从 0 开始,字符串连接支持 + 号连接。 `CharSequence` 类型的参数，是一种接口类型，所有字符串都属于这个接口。**

**`String` 类，String 类对象不可改变，一旦创建，内容不能再改变**

* `char charAt(int index)`    

  返回给定位置的代码单元。除非对底层的代码单元感兴趣，否则不需要调用这个方法

* `int codePointAt(int index)`    

  返回从给定位置开始的码点

* `int offsetByCodePoints(int startIndex, int cpCount)`           

  返回从 `startIndex` 代码点开始，位移 `cpCount` 后的码点索引

* `int compareTo(String other)`   

  按照字典排序，如果字符串位于 `other` 之前，返回一个负数；如果字符串位于 `other` 之后，返回一个正数,如果两个字符串相等，返回 0

* `int compareToIgnoreCase(String str)`         

  `compareTo` 的忽略大小写版本

* `IntStream codePoints().toArray()`  

  将这个字符串的码点作为一个流返回。调用 `toArray` 将它们放在一个数组中

* `new String(int[] codePoints, int offset, int count)`   

  用数组中从 `offset` 开始的 `count` 个码点构造一个字符串

* `Boolean equals(Object other)`      

  如果字符串与 `other` 相等，返回 `true`

* `Boolean equalsIgnoreCase(String other)`        

  如果字符串与 `other` 相等（忽略大小写），返回 `true`

* `boolean startsWith(String prefix)`       

  如果字符串以 `prefix` 开头，返回 `true`

* `boolean endsWith(String suffix)`           

  如果字符串以 `suffix` 结尾，则返回 `true`

* `String concat(str)`                  

  将本字符串和字符串 str 连接，返回一个新字符串

* `int length()`        

  返回字符串的长度

* `int codePointCount(int startIndex, int endIndex)`  

  返回 `startIndex` 和 `endIndex -1` 之间的代码点数量。没有配成对的代码代用字符将计入代码点

* `String replace(CharSequence oldString, CharSequence newString)`  

  返回一个新字符串。这个字符串用 `newString` 代替原始字符串中所有的 `oldString`, 可以用 `String` 或 `StringBuilder` 对象作为 `CharSequence` 参数

* `String toLowerCase()`      

  返回一个新字符串，将原始字符串中的大写字母改为小写

* `String toUpperCase()`        

  返回一个新的字符串。将字符串中的小写字母改为大写

* `String trim()`               

  返回一个新字符串。这个字符串将删除了原始字符串头部和尾部的空格

* `String join(CharSequence delimiter, CharSequence... elements)`       

  返回一个新字符串，用给定的定界符连接所有元素

* `boolean startsWith(String prefix, int toffset)`          

  测试从指定索引开始的此字符串的子字符串是否以指定的前缀开头

* `boolean startsWith(String prefix)`                       

  测试字符串是否以指定前缀开始

* `boolean endsWith(String suffix)`                         

  如果字符串以特定的后缀结束，返回 `true`

* `boolean contains(CharSequence s)`                        

  当且仅当此字符串包含指定 `CharSequence` 时，才返回 `true`

* `substring(int beginIndex)`                               

  返回该字符串的子串，从特定位置 `beginIndex` 的字符开始到字符串的结尾

* `substring(int beginIndex, int endIndex)`            

  返回该字符串的子串，从特定位置 `beginIndex` 的字符开始到下标为 `endIndex-1` 的字符，

* `int indexOf(ch char)`                

  返回字符串中出现的第一个 `ch` 的下标，如果没有匹配，返回 -1

* `int indexOf(ch, fromIndex)`    

  返回字符串中 `fromIndex` 之后出现的第一个 `ch` 的下标，没有匹配返回 -1

* `int indexOf(String s)`        

  返回字符串中出现的第一个字符串的 `s` 的下标，如果没有匹配返回 -1

* `int indexOf(String s, int fromIndex)`  

  返回字符串中 `fromIndex` 之后出现的第一个字符串 `s` 的小标，如果没有匹配的，返回 - 1

* `int lastIndexOf(char ch)`            

  返回字符串中出现的最后一个 `ch` 的下标。如果没有匹配的，返回 -1

* `int lastIndexOf(ch, fromIndex)`      

  返回字符串中 `fromIndex` 之前出现的最后一个 `ch` 的下标。如果没有匹配的返回 -1

* `int lastIndexOf(string s)`           

  返回字符串中出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1

* `int lastIndexOf(String s, int fromIndex)`  

  返回字符串中 `fromIndex` 之前出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1

* `String[] split(String regex)`              

  指定分隔符分割字符串，返回一个字符串数组

* `boolean matches(String regex)`             

  正则匹配

* `String replaceAll(String regex, String replacement)`         

  正则表达式全局替换

* `String replaceFirst(String regex, String replacement)`       

  正则表达式首次出现替换

* `void getChars(int srcBegin, int srcEnd, char dst[], int dstBegin)`    

  将下标从 `scrbegin` 到 `srcEnd -1` 的字串赋值到字符数组 `dst` 中下标从 `detBegin` 开始的位置

* `valueOf(mixd)`              

* `String.format(format, item1, item2)`          

  返回一个格式化的字符串

