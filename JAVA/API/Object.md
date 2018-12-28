## java.util.Object 1.0

* `int hashCode()` 		// 返回对象的散列码。散列码可以使任意的整数，包括正数或负数。两个相等的对象要求返回相等的散列码
* `toString()` 		// 返回表示对象值的字符串

## java.lang.Object 1.0

* `Class getClass()` 				// 返回包含对象信息的类对象。
* `boolean equals(Object otherObject)`                                     // 比较两个对象是否相等，如果两个对象指向同一块存储区域，方法返回 true；否则方法返回 false。在自定义的类中，应该覆盖这个方法
* `String toString()`                             // 返回描述该对象值的字符串。在自定义的类中，应该覆盖这个方法

## java.lang.Class 1.0

* `String getName()`				// 返回这个类的名字
* `Class getSupperclass()`                                                  // 以 Class 对象的形式返回这个类的超类信息

## java.util.Objects 7.0

* `static int hash(Object... Objects)` 			// 返回一个散列码，由提供的所有对象的散列码组合而得到的
* `static int hashCode(Object a)`     			// 如果 a 为 null 返回 0，否则返回 `a.hashCode()`

## java.lang(Integer|Long|Short|Byte|Double|Float|Character|Boolean) 1.0

* `static int hashCode((int|long|short|byte|double|float|char|boolean) value )` 	// 返回给定值的散列码

## java.util.Arrays 1.2

* `static int hashCode(type[] a)`    // 计算数组 a 的散了码。组成这个数组的元素类型可以是 `object`，`int`，`long`，`short`，`char`，`byte`，`boolean`，`float` ，`double`

