*java.math.BigInteger.java*

```java
// 返回与 other 和
BigInteger add(BigInteger other);
// 返回与 other 差
BigInteger subtract(BigInteger other);
// 返回与 other 乘积
BigInteger multiply(BigInteger other);
// 返回与 other 商
BigInteger divide(BigInteger other);
// 返回与 other 余数
BigInteger mod(BigInteger other);
// 如果这个大整数与另一个整数 other 相等，返回 0；如果这个大整数小于另一个大整数，返回负数，否则，返回正数
int compareTo(BigInteger other);
// 返回 x 的 BigInteger
static BigInteger valueOf(long x);
```