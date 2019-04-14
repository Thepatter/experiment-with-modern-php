## java.util.Objects

* `static int hash(Object... Objects)` 			

  返回一个散列码，由提供的所有对象的散列码组合而得到的

* `static int hashCode(Object a)`     			

  如果 a 为 null 返回 0，否则返回 `a.hashCode()`

## java.lang(Integer|Long|Short|Byte|Double|Float|Character|Boolean) 1.0

* `static int hashCode((int|long|short|byte|double|float|char|boolean) value )` 	

  返回给定值的散列码

## java.util.Arrays

* `static int hashCode(type[] a)`    

  计算数组 a 的散了码。组成这个数组的元素类型可以是 `object`，`int`，`long`，`short`，`char`，`byte`，`boolean`，`float` ，`double`

