*java.awt.event.InputEvent*
```java
int getModifiersEx();

// 返回事件扩展的或按下的修饰符。使用下面的掩码值检测返回值 `BUTTON1_DOWN_MASK`,`BUTTON2_DOWN_MASK`, `BUTTON3_DOWN_MASK`, `SHIFT_DOWN_MASK`, `CTRL_DOWN_MASK`, `ALT_DOWN_MASK`, `ALT_GRAPH_DOWN_NASK`, `META_DOWN_NASK`

static String getModifiersExText(int modifiers);

// 返回用给定标志集描述的扩展或按下的修饰符字符串

```