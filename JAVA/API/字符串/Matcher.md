## java.util.regex.Matcher

* `boolean matches()`

  如果输入匹配模式，则返回 true

* `boolean lookingAt()`

  如果输入的开头匹配模式，则返回 true

* `boolean find()`

* `boolean find(int start)`

  尝试查找下一个匹配，如果找到了另一个匹配，则返回 true

  start		开始查找的索引位置

* `int start()`

* `int end()`

  返回当前匹配的开始索引和结尾之后的索引位置

* `String group()`

  返回当前的匹配

* `int groupCount()`

  返回输入模式中的群组数量

* `int start(int groupIndex)`

* `int end(int groupIndex)`

  返回当前匹配中的给定群组的开始和结尾之后的位置

  `groupIndex`       群组索引（从 1 开始），或者表示整个匹配的 0

* `String group(int groupIndex)`

  返回匹配给定群组的字符串

  `groupIndex`          群组索引（从 1 开始），或者表示整个匹配的 0

* `String replaceAll(String replacement)`

* `String replaceFirst(String replacement)`

  返回从匹配器输入获得的通过将所有匹配或第一个匹配用替换字符串替换之后的字符串

  `replacement`       替换字符串，它可以包含用 `$n` 表示的对群组的引用，这时需要用 `\$` 来表示字符串中包含一个 `$` 符号

* `static String quoteReplacement(String str)`

  引用 `str` 中的所有 `\` 和 `$`

* `Matcher reset()`

* `Matcher reset(CharSequece input)`

  复位匹配器的状态。这个方法将使匹配器作用于另一个不同的输入。这个方法都返回 this

  