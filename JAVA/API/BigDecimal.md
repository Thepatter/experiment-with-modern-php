## java.math.BigDecimal.java

// 返回这个大实数与另一个大实数 other 的和，差，积，商。要想计算商，必须给出舍入方式。RoundingMode.HALF_UP 为四舍五入

* `BigDecimal add(BigDecimal other)`

* `BigDecimal subtract(BigDecimal other)`

* `BigDecimal multiply(BigDecimal other)`

* `BigDecimal divide(BigBecimal other, RoundingMode mode)`

// 返回值为 x 或 x/10 的一个大实数

* `static BigDecimal valueOf(long x)`

* `static BigDecimal valueOf(long x, int scale)`

* `int compareTo(BigDecimal other)`         // 如果这个大实数与另一个大实数相等，返回 0；小于返回负数，大于正数
