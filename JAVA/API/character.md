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

 