*java.time.format.DateTimeFormatter*

```java
// 返回指定的风格格式化日期
static DateTimeFormatter ofLocalizedDate(FormatStyle dateStyle);
// 返回指定的风格格式化时间
static DateTimeFormatter ofLocalizedTime(FormatStyle dateStyle);
// 返回指定的风格格式化日期时间
static DateTimeFormatter ofLocalizedDateTime(FormatStyle dateStyle, FormatStyle timeStyle);
// 返回新 locale 的当前格式器副本
DateTimeFormatter withLocale();
// 返回格式化给定日期/时间所产生的字符串
String format(TemporalAccessor temporal);
```

