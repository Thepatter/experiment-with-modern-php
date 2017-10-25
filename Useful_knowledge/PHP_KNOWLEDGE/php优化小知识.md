**PHP 代码块里的变量在需要时候才声明,如果提前声明而后面未用到该变量就返回了或者跳出了,**
**则会导致内存额外开销.对数据类型与数据尽量进行判断,避免出现错误.**
**调用一个函数,若没有正确返回,则返回值为 null**
`mixed array_rand (array $array [, int $num = 1])` 函数返回的是**数组的键而非值**

**empty 函数判读数组的时候，如果数组的值为空字符串，则函数返回 false**
```php
$array = ['', ''];
$array = [0, 0];
$array = [false, false];
$array = [null, null];
empty($array); // false
```
过滤数组的值http://php.net/manual/zh/function.array-filter.php
**嵌套 for 循环会先判断内存循环条件，先循环完内层循环，然后再判断外层循环条件，循环外层，然后在循环内层**
```php
for ($a = 0; $a < 2; $a++) {
    for ($b = 0; $b < 3; $b++) {
        echo '内层循环'. "<br>";
    }
    echo "外层循环"."<br>";
}
```
结果为`内层循环
内层循环
内层循环
外层循环
内层循环
内层循环
内层循环
外层循环`
