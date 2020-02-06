*java.util.regex.Pattern*

```java
// 产生一个流，它的元素是输入中由该模式界定的部分
Stream<String> splitAsStream(CharSequence input);
/**
 * 编译正则为模式对象
 * @param expression 正则表达式
 * @flags CASE_INSENSITIVE UNICODE_CASE MULTLINE UNIX_LINES DOTALL CANON_EQ
 */
static Pattern complie(String expression);
static Pattern complie(String expression, int flags);
// 返回一个 macher 对象，用它在输入中定位模式的匹配
Matcher matcher(CharSequence input);
/**
 * 将输入分割成标记，其中模式指定了分隔符的形式，返回标记数组，分隔符并非标记的一部分
 * input 要分隔成标记的字符串
 * limit 所产生的字符串的最大数量，如果已经发现了 limit -1 个匹配的分隔符，那么返回的数组中的最后一项就包含
 *       了所有剩余未分割的输入。如果 limit<=0，那么坠尾的空字符串将不会置于返回的数组中
 */
String[] split(CharSequence input);
String[] split(CharSequence input, int limit);
Stream<String> splitAsStream(CharSequence input);
```