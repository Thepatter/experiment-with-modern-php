*java.nio.Buffer*
```java
boolean hasRemaining();

// 如果当前的缓冲区位置没有到达这个缓冲区的界限位置，则返回 true

int limit();

// 返回这个缓冲区的界限位置，即没有任何值可用的第一个位置

Buffer clear();

// 通过将位置复位到 0，并将界限设置到容量，使这个缓冲区为写出做好准备。返回 `this`

Buffer flip();

// 通过将界限设置到位置，并将位置复位到 0，使这个缓冲区为读入做好准备。返回 `this`

Buffer rewind();

// 通过将读写位置复位到 0，并保持界限不变，使这个缓冲区为重新读入相同的值做好准备。返回 `this`

Buffer mark();

// 将这个缓冲区的标记设置到读写位置，返回 `this`

Buffer reset();

// 将这个缓冲区的位置设置到标记，从而允许被标记的部分可以再次被读入或写出，返回 `this`

int remaining();

// 返回剩余可读入或可写出的值的数量，即界限或位置之间的差异

int position();

void position(int newValue);

// 返回这个缓冲区的位置

int capacity();

// 返回这个缓冲区的容量

```