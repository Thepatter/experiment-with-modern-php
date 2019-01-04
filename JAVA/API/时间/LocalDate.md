## java.time.LocalDate 8

* `static LocalTime now()`

  构造一个表示当前日期的对象

* `static LocalTime of(int year, int month, int day)`：构造一个表示给定日期的对象

* `int getYear()`

  得到当前日期的年

* `int getMonthValue`

  得到当前日期的月

* `int getDayOfMonth`

  得到当前日期的日

* `DayOfWeek getDayOfWeek`

  得到当前日期是星期几，作为 `DayOfWeek` 类的一个实例返回。调用 `getValue` 来得到 1 ～ 7 之间的一个数，表示星期几

* `LocalDate plusDays(int n)`

  生成当前日期之后的 n 天的日期

* `LocalDate minusDays(int n)`

  生成当前日期之前的 n 天的日期

