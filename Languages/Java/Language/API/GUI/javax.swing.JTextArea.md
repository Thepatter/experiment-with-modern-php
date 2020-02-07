*javax.swing.JTextArea*
```java
JTextArea();

JTextArea(int rows, int cols);

JTextArea(String text, int rows, int cols);

// 构造一个新的文本区对象

void setColumns(int cols);

// 设置文本区应该使用的首先列数

void setRows(int rows);

// 设置文本区应该使用的首选行数

void append(String newText);

// 将给定的文本追加到文本区中的已有文本的尾部

void setLineWrap(boolean warp);

// 打开或关闭换行

void setWrapStyleWord(boolean word);

// 如果 `word` 是 true，超长的行会在字边框处换行。如果为 false，超长的行被截断而不考虑字边框

void setTabSize(int c);

// 将制表符（tab stop）设置为 c 列。制表符不会被转化为空格，但可以让文本对齐到下一个制表符处

```