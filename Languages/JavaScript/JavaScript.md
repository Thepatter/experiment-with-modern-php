### JavaScript 

#### 语言特性

#### 变量类型

js 中代码块不会创建新的作用域，变量应该被定义在函数的头部，而不是代码块中。

以下值为假：false、null、undefined、' '、0、NaN，其他所有值被当做真：true、“false"、所有对象

typeof 运算产生值有：'number'、‘string'、’boolean'、'function'、’object‘（数组或 null 结果为 'object'）

##### Number

```js
let myNum = Number("230") // 转换成数字
let numString = myNum.toString(); 	// 转换成字符串
```

##### Undefined

Node 的 delete 语句返回值为布尔类型，如果变量被删除，返回 true，如果变量没有删除，返回 false。一般的 object 属性，可以用 delete 删除，内建的属性、一般对象调用 delete 返回 false，delete 关键字不能用于这些类型

在 Node 中一个普通的变量不能使用 delete，但变量本身可以设置为 undefined 变量值也可以设置为 undefined

##### String

字符串字面量可以被包在一对单引号或双引号中，它可以包含 0 个或多个字符。所有字符都是 16 位。JavaScript 没有字符类型，要表示单个字符，创建仅包含一个字符的字符串

```js
// 字符串 length 属性 "serven".length;
"A" === "\u0041"
'c' + 'a' + 't' === 'cat' // true
```

字符串不可变，一旦字符串被创建，就永远无法改变它。+ 运算符连接字符串创建一个新字符串

##### Boolean

true/false

##### 函数

###### 函数对象

函数对象连接到 `Function.prototype`（该原型本身连接到 `Object.prototype`），每个函数在创建时会附加两个隐藏属性：函数的上下文和实现函数行为的代码。每个函数对象在创建时的 prototype 值是一个拥有 constructor 属性且值为该函数的对象。

###### 函数字面量

函数字面量定义了函数值，它可以有一个可选的名字，用于递归调用自己。可以指定一个参数列表，这些参数像变量一样，在调用时由传递的实际参数初始化。函数字面量可以出现在任何允许表达式的地方。

###### 匿名函数

没有名称的函数，匿名函数通常是为了特殊的目的并且只暴露给声明它们的代码

匿名函数通常是一个 lambda 函数。lambda 函数可以将函数赋值给一个变量并且这个变量可以像其他变量那样运行。lambda 函数可以使用普通的函数调用语法通过它被赋值的变量进行调用。匿名函数被赋值给变量或者被传递给函数作为函数的参数，并且可以通过该变量使用函数调用语法调用

```javascript
var f = function (a) {
    return a + 1;
}
var b = f(4);
```

###### 闭包

闭包是语言特性，当函数定义后，一个函数的外部上下文会被保存下来并且当函数被调用时提供给函数使用。在这个被保存的上下文中的任何变量的值一直是持久化的并且同一时间只有一个值，对这个函数的所有调用共享同一个上下文并且引用同样的变量

函数可以被定义在其他函数中，一个内部函数除了可以访问自己的参数和变量，同时也能自由访问把它嵌套在其中的父函数的参数与变量。通过函数字面量创建的函数对象包含一个连到外部上下文的连接（闭包）

```javascript
function f() {
    var b = 6;
    function g() {
        ++b;
        return b;
    }
    return g;
}
// 关闭闭包获取一个变量在函数被定义时的值，而不是当前调用时的值，同时使用外层函数和内存函数来关闭闭包。
var h = f();
var c = h(); // c = 7
var d = h(); // d = 8
```

变量 b 即使不是函数 g 的本地变量，但仍然可以被函数 g 使用，即使在函数 f 退出之后。变量 b（闭包术语上值）的行为就像是一个私有的全局变量。对函数 g 来说就像是一个全局变量但对函数 f 外面的所有代码确实隐藏的

##### 调用

调用函数时，除了声明时参数，每个函数还接收两个附加的参数：this、arguments。this 值取决于调用的模式（方法调用模式、函数调用模式、构造器调用模式、apply 调用模式），不同的模式在初始化参数 this 存在差异

如果实参值过多，超出的参数值会被忽略，如果实参值过少，缺少的值会被替换为 undefined，对参数值不会进行类型检查

* 方法调用模式

##### 对象

JavaScript 中对象分为：用户自定义对象；内建对象，宿主（浏览器、node）对象。除了：数字、字符串、布尔值、null、underfined 值，其他所有值都是对象（数字、字符串、布尔值拥有方法，但它们是不可变，数组、函数、正则都是对象）。JavaScript 中对象是可变的键控集合。

对象是属性的容器，其中每个属性都拥有名称（可以是空字符串在内的任意字符串）和值（除 undefined 值之外的任何值）。

JavaScript 里的对象是无类型的，它对新属性的名字和属性的值没有限制。对象适合用于汇集和管理数据。对象可以包含其他对象，基于原型的系统，所有对象共享同一个对象（原型）。原型可以为所有使用该原型的对象提供变量和方法。允许对象继承另一个对象的属性。正确地使用它能减少初始化时消耗的时间和内存。

###### 创建对象

* 构造函数

  构造函数定义了对象的属性和方法（不会明确创建一个对象和返回任何值），约定构造函数以大写字母开头

  ```javascript
  function Person(name) {
  	this.name = name;
  	this.greeting = function() {
  		alert('Hi I\'m ' + this.name + '.')
  	}
  }
  // 每次调用构造函数时，都会重新定义一次 greeting()
  let person = new Person('Bob');
  ```

* `object()` 构造函数

  使用 `new Object()` 创建一个空对象，在该空对象上添加属性和方法

  ```javascript
  let chris = new Object();
  chris.name = 'Chris';
  chris.greeting = function () { alert('Hi I\'m ' + this.name + '.'); }
  // 传递对象文档给 Object 方法
  let chr = new Object({
      name: 'Chris',
      age: 38,
      greeting: function () {
          alert('Hi! I\'m '  + this.name + '.')
      }
  });
  ```

* `create()` 方法

  内置的 `create()` 方法，允许基于现有对象创建新的对象，实际从指定原型对象创建一个新的对象

  ```javascript
  // person2 基于 person1 创建，它们具有相同的属性和方法。
  let person2 = Object.create(person);
  ```
  

###### 对象原型

每个对象都连接到一个原型对象，并且它可以从中继承属性，所有通过字面量创建的对象都连接到 `Object.prototype`，当创建一个新对象时，可以选择某个对象作为它的原型。当对某个对象做出改变时，不会触及该对象的原型。

每个对象拥有一个原型对象，对象以其原型为模板、从原型继承方法和属性。原型对象也可能拥有原型，并从中继承方法和属性，形成原型链。这些属性和方法定义在 Object 的构造器函数的 `prototype` 属性上，而非对象实例本身。

```javascript
function doSomething(){}
doSomething.prototype.foo = "bar";
let doSomeInstancing = new doSomething();
doSomeInstancing.prop = "some value";
console.log("doSomeInstancing.prop:      " + doSomeInstancing.prop);  // some value
console.log("doSomeInstancing.foo:       " + doSomeInstancing.foo);		// bar
console.log("doSomething.prop:           " + doSomething.prop);			// undefined
console.log("doSomething.foo:            " + doSomething.foo);			// undefined
console.log("doSomething.prototype.prop: " + doSomething.prototype.prop);	// undefined
console.log("doSomething.prototype.foo:  " + doSomething.prototype.foo);	// bar
```

* 委托

  原型连接只有在检索值得时候才被用到，如果尝试去获取对象得某个属性值，但该对象没有此属性名，会尝试从原型对象中获取属性值。如果那个原型对象也没有属性值，再从它得原型中寻找，直到最后到达终点 `Object.proptotype`，如果不存在原型链中，结果是 `undefined`。如果仅需要获取自身属性而不检索原型链上属性，可以使用 `hasOwnProperty`

  `doSomeInstancing.__proto__` 属性就是 `doSomething.prototype`，当访问 `doSomeInstancing` 的一个属性，浏览器首先查找 `doSomeInstancing` 是否有这个苏醒，如果没有，会在 `doSomeInstancing.__proto__` 中查找这个属性。如果没有，会在 `doSomeInstancing.__proto__.__proto__` 查找，如果原型链上的不存在这个属性，则该属性 `undefined`

* delete 删除属性

  delete 运输符可以删除对象得属性，如果对象包含该属性，该属性就会被移除，不会触及原型链中得任何属性，删除对象得属性会让来自原型链中的属性显现出来

原型链中的方法和属性没有被复制到其他对象（它们被访问需要通过原型链的方式），没有官方的方法用于直接访问一个对象的原型对象（原型链中的连接定义在内部属性）。上游对象的方法不会复制到下游的对象实例中；下游对象本身虽然没有定义这些方法，但浏览器会通过上溯原型链、从上游对象中获取更新。

常见对象定义模式是，在构造器中定义属性，在构造器 `prototype` 属性上定义方法。

* prototype 属性，继承成员被定义的地方

  继承的属性和方法是定义在 `prototype` 属性之上

  ```javascript
  Person.prototype.farewell = function () {alert(this.name.first + ' has left the building')}
  ```
  
  整个继承链动态更新了，任何由此构造器创建的对象实例都自动获得了这个方法
  
* constructor 属性，指向了用于构造此实例对象的构造函数

  ```javascript
  // 创建对象
  let person3 = new person1.constructor('jim');
  // 获取对象实例构造器名字 instanceName.constructor.name;
  let instanceName = instanceName.constructor.name;
  ```

  

###### 字面量

对象字面量是一种可以方便地按指定规格创建新对象的表示法。属性名可以是标识符或字符串。这些名字被当做字面量名而不是变量名来对待，对象的属性名在编译时才能知道。属性值就是表达式。对象字面量就是包围在一对花括号中的零或多个名/值对。对象字面量可以出现在任何允许表达式出现的地方。

```js
var empty_object = {};
// 对象字面量属性的引号不是必须的，但包含不合法的 - 时则必须
var stooge = {
    "first-name": "jim",
    "last-name": "green"
}
```

###### Array

数组是一个单个对象

###### 浏览器对象

DOM 将文档转为文档对象，代表着加载到浏览器窗口的当前网页。DOM 把文档表示为一棵树。

* 节点对象
  
  元素节点（nodeType = 1）对应 HTML 标签，标签的名字就是元素的名字（通过 `getElementById` 获取特定 id 属性的对象、`getElementsByTagName` 获取特定标签的对象数组、`getElementsByClassName` 获取特定 class 属性的对象数组）、文本节点（nodeType = 2）对应标签内容（通过 node.nodeValue 操作文本属性值，获取元素文本 `node.childNodes[0].nodeValue`）、属性节点（nodeType = 3）对应标签属性(可以通过 `getAttribute`/`setAttribute` 来获取和设置属性节点值)
  

BOM

浏览器对象，对应着浏览器窗口本身。