*java.lang.CharSequence*

```java
// 返回当前字符串所有 unicode 码点构成的流
IntStream codePoints();
// 返回给定索引
char charAt(int index);
// 返回字符序列的长度，该长度是该字符串 16-bit 长
int length();
// 返回 startIndex ~ endIndex - 1 的子 CharSequence
CharSequence subSequence(int startIndex, int endIndex);
// 返回这个序列中构成字符串
String toString();
```