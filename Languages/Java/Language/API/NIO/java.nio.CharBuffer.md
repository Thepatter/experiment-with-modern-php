*java.nio.CharBuffer*
```java
char get();

CharBuffer get(char[] destination);

CharBuffer get(char[] destination, int offset, int length);

// 从这个缓冲区的当前位置开始，获取一个 `char` 值，或者一个范围内的所有 `char` 值，然后将位置向前移动越过所有读入的字符。最后两个方法返回 `this`

CharBuffer put(char c);

CharBuffer put(char[] source);

CharBuffer put(char[] source, int offset, int length);

CharBuffer put(String source);

CharBuffer put(CharBuffer source`);

// 从这个缓冲区的当前位置开始，放置一个 `char` 值，或者一个范围内的所有 `char` 值，然后将位置向前移动越过所有被写出的字符。当防止的值是从 `CharBuffer` 读入时，将读入所有剩余字符。所有方法将返回 this

```