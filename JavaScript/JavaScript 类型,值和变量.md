## JavaScript 类型,值和变量

#### JavaScript的数据类型分为两类:

* 原始类型(primitive type)
* 对象类型(object type).

###  JavaScript原始类型

* 数字
* 字符串
* 布尔值
* 特殊类型的原始值 null(空)
* undefined(未定义)

#### JavaScript对象类型

​     __对象是属性的集合,每个属性都由"名/值"(值可以是原始值,也可以是对象)构成.普通的 JavaScript 对象是"命名值"的无序集合__

* 特殊对象--数组(array),表示带编号的值的有序集合.数组具有一些和普通对象不同的特有行为特性

* 特殊对象--函数.函数是具有与它相关联的可执行代码的对象,通过调用函数来运行可执行代码,并返回运算结果.JavaScript

* 对象都是真值,并且 JavaScript 可以将它们当做普通对象来对待.如果函数用来初始化(使用 new 运算符)一个新建对象,函数为构造函数,每个构造函数定义了一类 (class)

* 对象--由构造函数初始化的对象组成的集合.类可以看做是对象类型的子类型.

### 变量

​      	__JavaScript 变量是无类型的( untyped ), 变量可以被赋予任何类型的值,一个变量可以重新赋予不同类型的值,使用 var 关键字来声明变量.JavaScript 采用__

​       __词法作用域.不在任何函数内声明的变量为全局变量,它在 JavaScript 程序中的任何地方都是可见的.在函数内声明的变量具有函数作用域,并且只在函数内可见__

#### 数字

​     __JS不区分整数值和浮点数值.JS中的所有数字都用浮点数表示,当一个数字直接出现JS中,则为数字直接量,JavaScript预定义全局变量Infinity和NaN,用来表示正无穷大和非数字__

#### 文本

​    __字符串是一组由16位值组成的不可变的有序序列,每个字符通常来自于 Unicode 字符集.JavaScript 通过字符串类型来表示文本,字符串的长度是其所含16位值个数,JavaScript 字符串和数组的索引从0开始__

* 转义字符

  |   `\n`   |            `换行符(\u000A)`            |
  | :------: | :------------------------------------: |
  |   `\o`   |           `NUL 字符(\u0000)`           |
  |   `\b`   |           `退格符（\u0008)`            |
  |   `\t`   |          `水平制表符(\u0009)`          |
  |   `\v`   |          `垂直制表符(\u000B)`          |
  |   `\f`   |            `换页符(\u000C)`            |
  |   `\r`   |            `回车符(\000D)`             |
  |   `"`    |            `双引号(\u0022)`            |
  |   `'`    |            `单引号(\u0027)`            |
  |   `\`    |                 反斜线                 |
  |  `\xXX`  |  由两位十六进制数XX指定的Latin-1字符   |
  | `\uXXXX` | 由4位十六进制数XXXX指定的 Unicode 字符 |

#### 常见 bool 假值

 `underfined null 0 -0 Nan ""`

#### 全局对象

​     __全局对象的属性是全局定义的符号,JavaScript 程序可以直接使用.当 JavaScript 解释权启动时,或者任何 Web 浏览器刷新时,它将创建一个新的全局对象并给它一组定义的初始属性__

* 全局属性, 如 undefined, Infinity 和 NaN

* 全局函数, 如 isNaN(), parseInt() 和 eval() 

* 构造函数, 如 Date(), RegExp(), String(), Object() 和 Array()

* 全局对象, 如 Math 和 JSON

* 客户端 Javascript 来讲，window 对象定义了一些额外的全局属性。在代码的最顶级，不再任何函数内的 javascript 代码，可以使用 this 来引用全局对象

​    `var global = this // 定义一个引用全局对象的全局变量`

* 在客户端 javascript 中，在浏览器窗口中的所有 javascript 代码中， windows 对象充当了全局对象。这个全局 windows 对象有一个属性 window

* 引用其自身，它可以代替 this 来引用全局对象。window 对象定义了核心全局属性，针对 web 浏览器和客户端 javascript 定义了一少部分其他全局属性，

* 当初次创建的时候，全局对象定义 javascript 中所有的预定义全局值。这个特殊对象同样包含了为程序定义的全局值。如果代码声明了一个全局变量，

* 这个全局变量就是全局对象的一个属性。

#### 包装对象

​     __JavaScript 对象是一种复合值：是属性或已命名值的集合。通过 "." 符号来引用属性值，当属性值是一个函数的时候，称其为方法。通过`o.m()`来调用对象  o 中的方法__

```js
var s = "test";     //创建一个字符串
	s.len = 4;          //给它设置一个属性
var t = s.len       //查询这个属性
```

* t 的值是 undefined 。第二行代码创建一个临时字符串对象，并给其 len 属性赋值为4，随即销毁这个对象。第三行通过原始的(没有被修改过)字符串值创建一个新字符串对象尝试取其 len 属性，这个属性自然不存在

​    

* 在读取字符串，数字和布尔值的属性值或方法时候，表现的像对象一样名，但如果试图给其属性赋值，则会忽略这个操作。修改只是发生在临时对象身上，而这个临时对象并未继续保留下来。存取字符串，数字和布尔值的属性时创建的临时对象为包装对象。

* 不可变的原始值和可变的对象引用

* JavaScript 中的原始值(undefined、null、布尔值，数字和字符串)与对象(包括数组和函数)有着根本区别。原始值不可更改的。任何方法都无法更改一个原始值。

* 原始值的比较是值的比较：只有在它们值相等时候它们才相等。比较两个单独的字符串，仅当它们的长度相等且每个索引的字符都相等时，才相等。

* 对象和数组的值是可以修改的，对象的比较并非值的比较，即使两个对象包含同样的属性及相同的值，它们也是不相等的。各个索引元素完全相等的两个数组也不相等

* JavaScript 将对象称为引用类型，对象值都是引用，对象的比较是引用的比较，当且仅当它们引用同一个基对象时，它们才相等。

   ```j&#39;s
 var a = [];     //定义一个引用空数组的变量a

    var b = a;      // 变量b引用同一个数组

    b[0] = 1;       //通过变量b来修改引用的数组

    a[0]            // 1,变量a也会修改

    a === b         // true,a和b引用同一个数组，因此他们相等。
   ```

* 比较两个数组的函数

    ```js
function equalArrays(a, b) {
    if (a.length != b.length) {
        return fasle;
    }
    for (var i = 0; i < a.length; i++) {
        if (a[i] !== b[i]) {
            return false;
        }
        return ture;
    }
}
    ```

* JS 类型转换

  |         值          |     转为字符串     | 数字 | 布尔值 |         对象          |
  | :-----------------: | :----------------: | :--: | :----: | :-------------------: |
  |      undefined      |    "undefined"     | NaN  | false  |    throw TypeError    |
  |        null         |       "null"       |  0   | false  |    throw TypeError    |
  |        true         |       "true"       |  1   |  true  |   new Boolean(true)   |
  |        false        |      "false"       |  0   | false  |  new Boolean(false)   |
  |   ""（空字符串）    |         ""         |  0   | false  |    new String("")     |
  | "1.2"（非空，数字） |       "1.2"        | 1.2  |  true  |   new String("1.2")   |
  |        "one"        |       "one"        | NaN  |  true  |   new String("one")   |
  |          0          |        "0"         |  0   | false  |     new Number(0)     |
  |         -0          |        "0"         |  -0  | false  |    new Number(-0)     |
  |         NaN         |       "NaN"        | NaN  | false  |    new Number(NaN)    |
  |      Infinity       |     "Infinity"     |      |  true  | new Number(Infinity)  |
  |      -Infinity      |    "_Infinity"     |      |  true  | new Number(-Infinity) |
  |   1(无穷大，非零)   |        "1"         |  1   |  true  |     new Number(1)     |
  |    {}(任意对象)     |                    |      |  true  |                       |
  |   `[]`(任意数组)    |                    |  0   |  true  |                       |
  | `[9]`(一个数字元素) |        "9"         |  9   |  true  |                       |
  | `['a']`（其他数组） | 使用 `join()` 方法 | NaN  |  true  |                       |

* JS 类型转换和相等性

  `null == undefined  	//true`	

  `"0" == 0`

  `0 == false`

  `"0" == false`

* 显示类型转换，使用 `Boolean(), Number(), String(), Object()` 函数

#### 对象转换为原始值

* 对象到布尔值的转换，所有的对象（包括数组和函数）都转换为 true，对于包装对象亦是如此。

* 对象到字符串和对象到数字的转换是通过调用待转换对象的一个方法来完成的，JavaScript 对象有两个不同的方法来执行转换。一种是通过 toString()，返回这个对象的字符串。({x:1, y:2}).toString()  // => "[object Object]". 另一种为 valueOf()，如果存在任意原始值，它就默认将对象转换为表示它的原始值，如果对象是复合值，默认则返回对象本身。日期类返回 unix 毫秒数

#### 变量作用域

__一个变量的作用域(scope)是程序源代码中定义这个变量的区域。全局变量拥有全局作用域，在 JavaScript 代码中的任何地方都是有定义的。在函数内声明的变量只在函数体内有定义。是局部变量，作用域是局部性，函数参数也是局部变量，只在函数体内有定义__

* 在函数体内，局部变量的优先级高于同名的全局变量，如果在函数内声明的一个局部变量或者函数参数中带有的变量和全局变量重名，那么全局变量就被局部变量所掩盖

```js
scope = "global"; 			// 声明一个全局变量
function checkscope() {
    scope = "local"			// 修改了全局变量
    myscope = "local"		// 显示声明了新的全局变量
    return [scope, myscope];	// 返回两个值
}
checkscope()			// ["local", "local"]
```

* 全局变量作用域声明是可以不加var,局部变量时则必须使用var

```js
var scope = "global scope";			// 全局变量
    function checkscope() {
    	var scope = "local scope";		// 局部变量
    	function nested() {
    		var scope = "nested scope";		// 嵌套作用域内的局部变量
   			return scope;					// 返回当前作用域内的值
    	}
    	return nested();					
    }
checkscope()						// 嵌套作用域
```

* 函数作用域嵌套,每个函数都有它自己的作用域

```js
var scope = "global scope";			// 全局变量
function checkscope() {
    var scope = "local scope";		// 局部变量
    function nested() {
        var scope = "nested scope";		// 嵌套作用域内的局部变量
        return scope;					// 返回当前作用域内的值
    }
    return nested();					
}
checkscope()						// 嵌套作用域
```

* 函数作用域和声明提前,

  __块级作用域 在类 C 语言的编程语言中，花括号内的每段代码都具有各自的作用域，而且变量在声明前它们的代码段之外是不可见的。JavaScript 中没有块级作用域。JavaScript 使用了函数作用域：变量在声明它们的函数体以及这个函数嵌套的任意函数体内都是有意义的__

  ```js
  function test(o) {
      var i = 0;
      if (typeof 0 == "object") {
          var j = 0;
          for (var k = 0; k < 10; k++) {
              console.log(k);
          }
      }
      console.log(j);
  }
  ```


