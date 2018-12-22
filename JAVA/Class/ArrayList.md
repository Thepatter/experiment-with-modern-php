## java.util.ArrayList


* `ArrayList()`	// 创建一个空的列表

* `void add(o:E)`		// 增加一个新元素 o 到该列表的末尾

* `void add(index: int, o: E)`   	// 增加一个新元素 o 到该列表的指定下标处
  
* `void clear()`			// 清楚列表中的所有元素
  
* `boolean contains(o: Object)`		// 如果该列表包含元素 o, 则返回 true
  
* `E get(index: int)`		// 返回该列表指定下标位置的元素
  
* `int indexOf(o: Object)`	// 返回列表中第一个匹配元素的下标

* `boolean isEmpty()`			// 如果该列表不包含如何元素，则返回 true
  
* `int lastIndexOf(o: Object)`	// 返回列表中匹配的最后一个元素的下标
  
* `boolean remove(o: Object)`		// 去除列表中的一个元素。如果该元素被去除，则返回 true
  
* `boolean remove(index: int)`			// 去除指定下标位置的元素。如果该元素被去除，则返回 true

* `int size()`				            // 返回列表中的元素个数
  
* `E set(index: int, o: E)`			// 设置指定下标位置的元素，返回先前在此下标位置的元素