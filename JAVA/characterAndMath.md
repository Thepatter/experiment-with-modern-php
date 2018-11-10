### MATH 类常用方法
#### 三角函数方法：
* `double sin(doubel radians)`                返回以弧度为单位的角度的三角正弦函数值
* `double cos(double radians)`                返回以弧度为单位的角度的三角余弦函数值
* `double tan(double radians)`                返回以弧度为单位的角度的三角正切函数值
* `double toRadians(double degree)`           将以度为单位的角度值转换为以弧度表示
* `double toDegrees(double radians)`          将以弧度为单位的角度值转换为以度表示
* `double asin(double a)`                     返回以弧度为单位的角度的反三角正弦函数值
* `double acos(doubel a)`                     返回以弧度为单位的角度的反三角余弦函数值
* `double atan(double a)`                     返回以弧度为单位的角度的反三角正切函数值
#### 指数函数方法：
* `double exp(double x)`                      返回 e 的 x 次方
* `doubel log(double x)`                      返回 x 的自然底数
* `double log10(double x)`                    返回 x 的以 10 为底的对数
* `double pow(double a, double b)`            返回 a 的 b 次方
* `double sqrt(double x)`                     对于 x >= 0 的数字，返回 x 的平方根
#### 取整方法
* `double ceil(double x)`                     x 向上取整为它最接近的整数，该整数作为一个双精度值返回
* `double floor(double x)`                    x 向下取整为它最接近的整数，该整数作为一个双精度值返回
* `double rint(double x)`                     x 取整为它最接近的整数，如果 x 与两个整数的距离相等，偶数的整数作为一个双精度值返回
* `double round(double x)`                    x 如果 x 是单精度数，返回 (int) Math.floor(x + 0.5);如果 x 是双精度数，返回 (long) Math.floor(x + 0.5)
* `min(a, b), max(a, b)`                      重载的方法，返回与输入类型一致，返回两个数的最小，最大值
* `abs(x)`                                    重载的方法，返回一个数的绝对值，返回类型为输入的类型
* `double random()`                           生成大于等于0.0且小于1.0的 double 型随机数
### Character 类提供的字符比较方法，char 类范围为 Unicode 字符数值
* `char`                        类型代表单个字符，可以与数据类型互相转换
* `boolean isDigit(char ch)`                        确定指定字符是不是数字
* `boolean isDigit(int codePoint)`                  确定指定码点是否是数字
* `boolean isLetter(char ch)`                       确定指定字符是否是字母
* `boolean isLetter(int codePoint)`                 确定指定字符（码点）是否是字符
* `Boolean isLetterOrDigit(char ch)`                确定指定字符是否是字母或数字
* `Boolean isLetterOrDigit(int codePoint)`          确定指定字符是否是字母或数字
* `boolean isLowerCase(char ch)`                    确定指定字符是否为小写字母
* `boolean isLowerCase(int codePoint)`              确定指定字符(码点)是否为小写字母
* `Boolean isUpperCase(char ch)`                    确定指定字符是否为大写字母
* `boolean isUpperCase(int codePoint)`              确定指定字符（码点）是否为大写字母
* `char toLowerCase(ch char)`                       返回指定的字符的小写形式
* `int toLowerCase(int codePoint)`                  使用UnicodeData文件中的大小写映射信息将字符（Unicode代码点）参数转换为小写
* `char toUpperCase(char ch)`                       返回指定的字符的大写形式
* `char toUpperCase(int codePoint)`                 返回Unicode文件中大写参数的映射

### 大数字类，不损失精度
*java.math.BigInteger.java*
// 返回这个大整数和另一个大整数 other 的和，差，积，商以及余数
* `BigInteger add(BigInteger other)`            
* `BigInteger subtract(BigInteger other)`
* `BigInteger multiply(BigInteger other)`
* `BigInteger divide(BigInter other)`
* `BigInteger mod(BigInteger other)`
* `int compareTo(BigInteger other)`     // 如果这个大整数与另一个整数 other 相等，返回 0；如果这个大整数小于另一个大整数，返回负数，否则，返回正数
* `static BigInteger valueOf(long x)`       // 返回值等于 x 的大整数
*java.math.BigDecimal.java*
// 返回这个大实数与另一个大实数 other 的和，差，积，商。要想计算商，必须给出舍入方式。RoundingMode.HALF_UP 为四舍五入
* `BigDecimal add(BigDecimal other)`        
* `BigDecimal subtract(BigDecimal other)`
* `BigDecimal multiply(BigDecimal other)`
* `BigDecimal divide(BigBecimal other, RoundingMode mode)`
// 返回值为 x 或 x/10 的一个大实数
* `static BigDecimal valueOf(long x)`
* `static BigDecimal valueOf(long x, int scale)`
* `int compareTo(BigDecimal other)`         // 如果这个大实数与另一个大实数相等，返回 0；小于返回负数，大于正数
