## java.time.format.DateTimeFormatter

* `static DateTimeFormatter ofLocalizedDate(FormatStyle dateStyle)`

* `static DateTimeFormatter ofLocalizedTime(FormatStyle dateStyle)`

* `static DateTimeFormatter ofLocalizedDateTime(FormatStyle dateStryle, FormatStyle timeStyle)`

  返回用指定的风格格式化日期、时间或日期和时间的 `DateTimeFormatter` 实例

* `DateTimeFormatter withLocale(Locale locale)`

  返回当前格式器的具有给定 `Locale` 的副本

* `String format(TemporalAccessor temporal)`

  返回格式化给定日期/时间所产生的字符串

  