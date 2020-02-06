*java.lang.Character*

```java
// char 代表单个字符，范围为 Unicode MIN_VALUE=\u0000(0) MAX_VALUE=\uFFFF(63356)
// 字符/unicode码点是否为数字
static boolean isDigit(char ch);
static boolean isDigit(int codePoint);
// 字符/unicode码点是否为字母
static boolean isLetter(char ch);
static boolean isLetter(int codePoint);
// 字符/unicode码点是否为字母或数字
static boolean isLetterOrDigit(char ch);
static boolean isLetterOrDigit(int codePoint);
// 字符/unicode码点是否小写字母
static boolean isLowerCase(char ch);
static boolean isLowerCase(int codePoint);
// 字符/unicode码点是否为大写字母
static boolean isUpperCase(char ch);
static boolean isUpperCase(int codePoint);
// 返回字符的小写形式
static char toLowerCase(char char);
// 使用 UnicodeData 文件中的大小写映射信息将Unicode码点转换为 int
static int toLowerCase(int codePoint);
// 返回字符大写形式
static char toUpperCase(char ch);
// 使用 UnicodeData 文件中的大小写映射信息将Unicode码点转换为 int
static int toUpperCase(int codePoint); 
// 返回指定 char[]/CharSequence 指定索引的 unicode code
static int codePointAt(CharSequence seq, int index);
static int codePointAt(char[] a, index)
```