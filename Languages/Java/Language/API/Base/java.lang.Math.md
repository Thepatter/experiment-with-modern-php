*java.lang.Math*

```java
// 返回以弧度为单位的角度的三角正弦函数值
double sin(double radians);
// 返回以弧度为单位的角度的三角余弦函数值
double cos(double radians);
// 返回以弧度为单位的角度的三角正切函数值
double tan(double radians);
// 将以度为单位的角度值转换为以弧度表示
double toRadians(double degree);
// 将以弧度为单位的角度值转换为以度表示
double toDegrees(double radians);
// 返回以弧度为单位的角度的反三角正弦函数值
double asin(double a);
// 返回以弧度为单位的角度的反三角余弦函数值
double acos(double a);
// 返回以弧度为单位的角度的反三角正切函数值
double atan(double a);
// 返回 e 的 x 次方
double exp(double x);
// 返回 x 的自然底数
double log(double x);
// 返回 x 的以 10 为底的对数
double log10(double x);
// 返回 a 的 b 次方
double pow(double a, double b);
// 对于 x >= 0 的数字，返回 x 的平方根
double sqrt(double x);
// x 向上取整为它最接近的整数，该整数作为一个双精度值返回
double ceil(double x);
// x 向下取整为它最接近的整数，该整数作为一个双精度值返回
double floor(double x);
// x 取整为它最接近的整数，如果 x 与两个整数的距离相等，偶数的整数作为一个双精度值返回
double rint(double x);
// 如果 x 是单精度数，返回 (int) Math.floor(x + 0.5);如果 x 是双精度数，返回 (long) Math.floor(x + 0.5)
double round(double x);
// 泛型方法
<T> min(T a, T b);
<T> max(T a, T b);
<T> abs(T x);
// 生成大于等于 0.0 且小于 1.0 的 double 型随机数
double random();
```