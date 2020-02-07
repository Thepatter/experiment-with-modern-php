*java.net.URLEncoder*
```java
static String encode(String s, String encoding);

// 采用指定的字符编码模式（如 “UTF-8”）对字符串 s 进行编码，并返回它的 URL 编码形式。在 URL 编码中，`A-Z`，`a-z`，`0-9`，`-`，`_`，`.`，`*` 等字符保持不变，空格被编码成 `+`，所有其他字符被编码成 `%XY` 形式的字节序列，其中 `0xXY` 为该字节十六进制数

```