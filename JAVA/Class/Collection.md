## java.util.Collection

* `boolean add(o: E)`                 // 添加一个新的元素 0 到集合中

* `boolean add(o: E)` 		    	  // 添加一个新的元素 o 到合集中

* `boolean addAll(c: Collection<? extends E>)`    // 将合集 c 中的所有元素添加到该合集中，并集
  
* `void clear()`        			// 从该合集删除所有元素
  
* `boolean contains(o: Object)`		// 如果该合集包含元素 o, 则返回 true
  
* `boolean containsAll(c: Collection<?>)`		// 如果该合集包含 c 中所有的元素，则返回 true

* `boolean equals(o: Object)`       			// 如果该合集等同于另外一个合集 o,则返回 true
  
* `int hashCode()`                  			// 返回该合集的哈希码
  
* `boolean isEmpty()`                   		// 如果该合集没有包含元素，则返回 true
  
* `boolean remove(o: Object)`               	// 从该合集中移除元素 o
  
* `boolean removeAll(c: Collection<?>)` 		// 从该合集中移除 c 中的所有元素， 差集

* `boolean retainAll(c: Collection<?>)`			// 保留同时位于 c 和该合集中的元素，交集
  
* `int size()`			// 返回该合集中的元素数目
  
* `Object[] toArray()`		// 为该合集中的元素返回一个 Object 数组
  
* `iterator(): Iterator<E>`		// 为该集合中的元素返回一个迭代器

   