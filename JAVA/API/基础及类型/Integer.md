## java.lang.Integer

* `int intValue()`

    以 `int` 的形式返回 `Integer` 对象的值（在 `number` 类中覆盖了 `intValue` 方法）
    
* `static String toString(int i)`

    以一个新的 string 对象的形式返回给定数值 i 的十进制表示
    
* `static String toString(int im, int radix)`

    返回数值 i 的基于给定 `radix` 参数进制的表示
    
* `static int parseInt(String s)`

* `static int parseInt(String s, int radix)`

    返回字符串 s 表示的整型数值，给定字符串表示的是十进制的整数（第一种），或者是 `radix` 参数进制的整数（第二种）
    
* `static Integer valueOf(String s)`

* `static Integer valueOf(String s, int radix)`

    返回用 s 表示的整型数值进行初始化后的一个新 `Integer` 对象，给定字符串表示的是十进制的整数（第一种），或者是 `radix` 参数进制的整数（第二种）