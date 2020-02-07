*java.awt.ToolKit*
```java
static ToolKit getDefaultToolKit();

// 获得默认的工具箱。工具箱包含有关 GUI 环境的信息

void beep();

// 发出一声铃响

static Toolkit getDefaultToolkit();

// 返回默认的工具箱

Dimension getScreenSize();

// 返回用户屏幕的尺寸

Cursor createCustomCursor(Image image, Point hotSpot, String name);

// 创建一个新的定制光标对象

// image 光标活动时显示的图像

// hotSpot     光标热点（箭头的顶点或十字中心）

// name    光标的描述，用来支持特殊的访问环境

```