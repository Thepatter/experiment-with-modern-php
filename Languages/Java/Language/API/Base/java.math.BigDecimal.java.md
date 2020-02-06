*java.math.BigDecimal.java*

```java
// 返回与 other 和
BigDecimal add(BigDeciaml other);
// 返回与 other 差
BigDecimal subtract(BigDeciaml other);
// 返回与 other 乘
BigDecimal multiply(BigDeciaml other);
// 返回与 other 商，必须给出舍入方式
BigDecimal divide(BigDeciaml other);
// 返回值为 x 或 x/10 的一个 bigdecimal
static BigDecimal valueOf(long x);
static BigDecimal valueof(long x, int scale);
// 比较相等，返回 0；小于返回负数，大于正数
int compareTo(BigDecimal other);
```