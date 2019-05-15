## PHP 常用 TIPS

### PHP 函数传参相关

* 标量类型是传值赋值 
* `array` 类型也是传值赋值,返回的是一个新数组,除非在形参用 `&` 符号,表明要传引用,则会修改源数组对应的引用
* `Object` 类型是传引用,修改对象状态后,源对象也会改变相应状态
* `serialize()` 函数不会生成一个指针指向对应参数的序列化对象,不是单一序列化对象.只是简单的返回该参数序列化后值

### String 字节数

* 使用 `mb_strlen()` 无论中文英文都是占一位,但 `strlen()` 中文则占三位

### 隐藏 PHP 信息

* 在 `php.ini` 文件里设置 `expose_php = off`，可以减少他们能获得的有用信息

* 使用 `web` 服务器配置文件

  **把 `PHP` 隐藏为另一种语言**

  ```nginx
  # 使 PHP 看上去像其它的编程语言
  AddType application/x-httpd-php .asp .py .pl
  ```

  **使用未知的扩展名作为 `PHP` 的扩展名**

  ```nginx
  # 使 PHP 看上去像未知的文件类型
  AddType addlication/x-httpd-php .bop .foo .133t
  ```

  **用 HTML 做 `PHP` 的文件后缀**

  这样所有的 HTML 文件都会通过 PHP 引擎，会为服务器增加一些负担

  ```c
  # 使 PHP 代码看上去像 HTML 页面
  AddType application/x-httpd-php .htm .html
  ```

  要让此方法生效，必须把 PHP 文件的扩展名改为以上的扩展名。这样就通过隐藏来提高了安全性。

  