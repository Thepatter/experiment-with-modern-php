*java.util.regex.Matcher*

```java
// 如果输入匹配模式，则返回 true
boolean matches();
// 如果输入的开头匹配模式，则返回 true
boolean lookingAt();
// 尝试查找下一个匹配，如果找到了另一个匹配，则返回 true
boolean find();
// 尝试查找下一个匹配，start 开始查找的索引位置，如果找到了另一个匹配，则返回 true
boolean find(int start);
// 返回当前匹配的开始索引和结尾之后的索引位置
int start();
int end();
// 返回当前的匹配
String group();
// 返回输入模式中的匹配数量
int groupCount();
// 返回当前匹配中的给定匹配开始或结尾之后的位置, groupIndex 匹配组索引从 1 开始，0 表示整个匹配，正则符号 ()
int start(int groupIndex);
int end(int groupIndex);
// 返回给定索引的组匹配
String group(int groupIndex);
/**
 * 返回从匹配器获得的将所有匹配或第一个匹配用替换字符串替换后的字符串
 * replacement 替换字符串，可包含用 $n 表示的对群组的引用，这时需要用 \$ 来表示字符串中包含一个 $ 符号
 */ 
String replaceAll(String replacemnet);
String replaceFirst(String replacement);
// 引用 str 中的所有 \ 和 $
static String quoteReplacement(String str);
// 复位匹配器状态
Matcher reset();
// 复位匹配器状态,将使匹配器作用于另一个不同的输入
Matcher reset(CharSequence input);
```

