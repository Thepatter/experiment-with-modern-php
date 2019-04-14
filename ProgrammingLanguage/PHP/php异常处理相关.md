## PHP  中异常处理相关顺序

### 方法里有 try catch finally 的执行顺序

* 不论是否出现异常，`finally` 块中的代码都会执行

* 当 `try` 和 `catch` 中有 `return` 时，`finally` 仍然会执行

* `finally` 是在 `return` 后面的表达式运算后执行的（此时并没有返回运算后的值，而是先把要返回的值保存起来）待 `finally` 执行完毕后再返回保存的值

* `finally` 里包含 `return`, 返回值是 `finally` 中 `return` 代码块的值

```php
try {} catch(){} finally{} return;
```

程序按照顺序依次执行 `try` 中代码，如果不出现异常，执行 `finally` 代码，然后执行 `return` 块中的代码；`try` 中出现异常则执行 `catch` 中的代码后，执行 `finally` 代码，执行最后的 `return` 代码

```php
try {return;} catch() {} finally {} return;
```

程序执行 `try` 块中的 `return` 之前的代码，如果出现异常则执行 `catch` 里的代码，然后执行 `finally` 里面的代码，最后，执行 `return` 代码；如果执行 `try` 块中 `return` 之前的代码没有出现异常，则将返回值保存起来，执行 `finally` 里代码，然后返回 `try` 中 `return` 代码值。

```php
try {} catch() {return;} finally{} return;
```

程序先执行 `try`，如果遇到异常执行 `catch` 块，然后将 `catch` 块中的 `return` 值保存起来，执行 `finally` 里代码，然后返回 `catch` 块的 `return` 值，最后的 `return` 不会执行；如果程序执行 `try` 没有异常，则会执行 `finally` 语句，然后执行最后的 `return` 语句

```php
try {return;} catch() {} finally {return;}
```

程序只会返回 `finally` 的 `return` 语句

```php
try {} catch() {return;} finally {return;}
```

程序只会返回 `finally` 的 `return` 语句



