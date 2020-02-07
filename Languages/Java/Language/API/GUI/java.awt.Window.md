*java.awt.Window*
```java
void toFront();

// 将这个窗口显示在其他窗口前面

void toBack();

// 将这个窗口移到桌面窗口栈的后面，并相应地重新排列所有的可见窗口

boolean isLocationByPlatform();

void setLocationByPlatform(boolean b);

// 获取和设置 `locationByPlatform` 属性。这个属性在窗口显示之前被设置，由平台选择一个合适的位置

void pack();

// 调整窗口大小，要考虑到其他组件的首选大小

```