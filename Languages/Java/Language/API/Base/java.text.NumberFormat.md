*java.text.NumberFormat*

```java
// 解析给定的字符串并返回数字值，如果输入字符串描述了一个浮点数，返回类型就是 Double，否则返回类型就是 Long。字符串必须以一个数字开头；以空白字符开头是不允许的。数字之后可以跟随其他字符，但它们都将被忽略。解析失败时抛出 ParseException 异常
Number parse(String s) throws PaseException;
// 返回一个 Locale 对象的数组，其成员包含有可用的 NumberFormat 格式器
static Locale[] getAvailableLocales();
// 为当前的或给定的 Locale 提供数字格式器
static NumberFormat getNumberInstance();
static NumberFormat getNumberInstance(Locale l);
// 为当前的或给定的 Locale 提供货币格式器
static NumberFormat getCurrencyInstance();
static NumberFormat getCurrencyInstance(Locale l);
// 为当前的或给定的 Locale 提供百分比格式器
static NumberFormat getPercentInstance();
static NumberFormat getPercentInstance(Locale l);
// 对给定的浮点数或整数进行格式化并以字符串的形式返回结果
String format(double x);
String format(long x);
// 设置或获取标志，该标志指示这个格式器是否应该只解析整数值
void setParseIntegerOnly(boolean b);
boolean isParseIntegerOnly();
// 设置或获取标志，该标志指示这个格式器是否会添加和识别十进制分隔符
void setGroupingUsed(boolean b);
boolean isGroupingUsed();
// 设置或获取证书或小数部分所允许的最大或最小位数
void setMinimumIntegerDigits(int n);
int getMinimumIntegerDigits();
void setMaximumIntegerDigits(int n);
int getMaxumumIntegerDigits();
void setMinimumFractionDigits(int n);
int getMinimumFractionDigits();
void setMaximumFractionDigits(int n);
int getMaxumumFractionDigits();
```