*java.lang.Integer*

```java
// 以一个新的 String 对象的形式返回给定数值的十进制表示
static String toString(int i);
// 返回数值 i 的基于给定 radix 参数进制的表示
static String toString(int im, int radix);
// 返回字符串 s 表示的整形数值，给定字符串表示的是十进制的整数
static int parseInt(String s);
// 返回字符串 s 表示的整型数值，radix 指定进制
static int parsetInt(String s, int radix) throws NumberFormatException;
// 返回 CharSequence 中 beginIndex ~ endIndex -1 的 radix 格式 int
static int parseInt(CharSequence s, int beginIndex, int endIndex, int radix) throws NumberFormatException;
// 返回 s 表示的整形数值的 Integer 对象
static Integer valueOf(String s);
// 返回 s 表示的整形数值 radix 参数进制的 Integer 对象
static Integer valueOf(String s, int radix);
// 比较 x，y 如果 x < y 返回 -1，x == y 返回 0，x > y 返回 1
static int compare(int x, int y);
int compareTo(Integer anotherInteger);
// 以 int 的形式返回 Integer 对象的值 MIN_VALUE = 0x80000000 MAX_VALUE = 0x7fffffff
byte byteValue();
short shortValue();
int intValue();
long longValue();
// 比较
static int compare(int x, int y);
static int compareTo(Integer anotherInteger);
// 返回 i 对应的 radix 格式
static String toUnsignedString(int i, int radix);
// 返回对应 hex
static void toHexString(int i);
// 返回八进制
static String toOctalString(int i);
// 返回二进制
static String toBinaryString(int i);
// 返回给定 int 值的字符串表示大小
static int stringSize(int x);
// 将八进制，十进制，十六进制 nm 解析为 Integer
static Integer decode(String nm) throws NumberFormatException;
static int sum(int a, int b);
static int max(int a, int b);
static int min(int a, int b);
static int signum(int it);
// 翻转二进制ibyte
static int reverseBytes(int i);
```

