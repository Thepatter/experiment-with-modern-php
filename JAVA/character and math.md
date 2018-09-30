###MATH 类常用方法
####三角函数方法：
* sin(radians)    返回以弧度为单位的角度的三角正弦函数值
* cos(radians)    返回以弧度为单位的角度的三角余弦函数值
* tan(radians)    返回以弧度为单位的角度的三角正切函数值
* toRadians(degree)   将以度为单位的角度值转换为以弧度表示
* toDegrees(radians)  将以弧度为单位的角度值转换为以度表示
* asin(a)         返回以弧度为单位的角度的反三角正弦函数值
* acos(a)         返回以弧度为单位的角度的反三角余弦函数值
* atan(a)         返回以弧度为单位的角度的反三角正切函数值
####指数函数方法：
* exp(x)  返回 e 的 x 次方
* log(x)  返回 x 的自然底数
* log10(x)    返回 x 的以 10 为底的对数
* pow(a, b)   返回 a 的 b 次方
* sqrt(x)     对于 x >= 0 的数字，返回 x 的平方根
#### 取整方法
* ceil(x)    x 向上取整为它最接近的整数，该整数作为一个双精度值返回
* floor(x)   x 向下取整为它最接近的整数，该整数作为一个双精度值返回
* rint(x)    x 取整为它最接近的整数，如果 x 与两个整数的距离相等，偶数的整数作为一个双精度值返回
* round(x)   x 如果 x 是单精度数，返回 (int) Math.floor(x + 0.5);如果 x 是双精度数，返回 (long) Math.floor(x + 0.5)
* min(a, b), max(a, b) 返回两个数的最小，最大值
* abs 返回一个数的绝对值
* random 生成大于等于0.0且小于1.0的 double 型随机数
#### Character 类提供的字符比较方法
* char 类型代表单个字符，可以与数据类型互相转换
* isDigit(ch)     如果指定的字符是一个数字，返回 true
* isLetter(ch)    如果指定的字符是一个字母，返回 true
* isLetterOrDigit(ch) 如果指定的字符是一个字母或数字，返回 true
* isLowerCase(ch) 如果指定的字符是一个小写字母，返回 true
* isUpperCase(ch) 如果指定的字符是一个大写字母，返回 true
* toLowerCase(ch) 返回指定的字符的小写形式
* toUpperCase(ch) 返回指定的字符的大写形式
**String 类型表示字符序列 String 是 java 的一个预定义类，String 不是基本类型，是引用类型。
使用引用类型声明的变量称为引用变量，它引用一个对象。
字符串下标从 0 开始
字符串连接支持 + 号连接**
* String 对象的常用方法
* length() 返回字符串中的字符数
* charAt(index) 返回字符串索引中指定位置的字符
* concat(str)  将本字符串和字符串 str 连接，返回一个新字符串
* toUpperCase()   返回一个新字符串，其中所有的字母大写
* toLowerCase()   返回一个新字符串，其中所有的字母小写
* trim()          返回一个新字符串，去掉两边的空白字符
* equals(s1)      如果该字符串等于字符串 s1 ,返回 true
* equalsIgnoreCase(s1)  如果该字符串等于字符串 s1，返回 true, 不区分大小写
* compareTo(s1)   返回一个大于0，等于0，小于 0 的整数，表明一个字符串是否大于，等于或小于 s1
* compareToIgnoreCase(s1)  和 compareTo 一样，区分大小写比较
* startsWith(prefix)    如果字符串以特定前缀开始，返回 true
* endsWith(suffix)      如果字符串以特定的后缀结束，返回 true
* contains(s1)          如果 s1 是该字符串的子字符串，返回 true
* substring(beginIndex) 返回该字符串的子串，从特定位置 beginIndex 的字符开始到字符串的结尾
* substring(beginIndex, endIndex) 返回该字符串的子串，从特定位置 beginIndex 的字符开始到下标为 endIndex-1 的字符，
* indexOf(ch)   返回字符串中出现的第一个 ch 的下标，如果没有匹配，返回 -1
* indexOf(ch, fromIndex)    返回字符串中 fromIndex 之后出现的第一个 ch 的下标，没有匹配返回 -1
* indexOf(s)    返回字符串中出现的第一个字符串的 s 的下标，如果没有匹配返回 -1
* indexOf(s, fromIndex)  返回字符串中 fromIndex 之后出现的第一个字符串 s 的小标，如果没有匹配的，返回 - 1
* lastIndexOf(ch)   返回字符串中出现的最后一个ch的下标。如果没有匹配的，返回 -1
* lastIndexOf(ch, fromIndex)   返回字符串中 fromIndex 之前出现的最后一个 ch 的下标。如果没有匹配的返回 -1
* lastIndexOf(s)    返回字符串中出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1
* lastIndexOf(s, fromIndex)   返回字符串中 fromIndex 之前出现的最后一个字符串 s 的下标，如果没有匹配的，返回 -1
#### 字符串和数字间的转换
* 可以将数值型字符串转换为数值。要将字符串转换为 int 值，使用 Integer.parseInt 方法 `int intValue = Integer.parseInt(intString)`
* 要将字符串转为 double 值，使用 Double.parseDouble 方法 `double doubleValue = Double.parseDouble(doubleString)`
* 将数值转为字符串，只需要简单使用字符串的连接操作符 `String s = number + ""`;
* 常用的格式标识符：%b 布尔值，%c 字符，%d 十进制整数，%f 浮点数，%e 标准科学计数法形式的数，%s 字符串
指定宽度和精度
%5c 输出字符并在这个字符条目前面家 4 个空格
%6b 输出布尔值，在 false 值前加一个空格，在true 值前加两个空格
%5d 输出整数条目，宽度至少为 5，如果该条目的数字位数小于 5，就在数字前面加空格，如果该条目的数字位数大于5，则自动增加宽度
%10.2f 输出的浮点条目宽度至少为 10，包括小数点和小数点后两位数字。
%10.2e 输出的浮点条目的宽度至少为 10，包括小数点，小数点后两位数字
%12s   输出的字符串宽度至少为 12 个字符。如果该字符串条目小于 12 个字符，就在该字符串前加空格，如果该字符串条目多于 12 个字符，则自动增加宽度
