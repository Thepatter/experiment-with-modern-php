*java.awt.Graphics*
```java
Color getColor();

void setColor(Color c);

// 获取或改变当前的颜色。所有后续的绘图操作都使用这个新颜色。c 新颜色

Font getFont();

void setFont(Font font);

// 获取或设置当前的字体。这种字体将被应用于后续的文本绘制操作中

void drawString(String str, int x, int y);

// 采用当前字体和颜色绘制一个字符串

// str 将要绘制的字符串

// x   字符串开始的 x 坐标

// y   字符串基线的 y 坐标

boolean drawImage(Image img, int x, int y, ImageObserver observer);

// 绘制一副非比例图形。这个调用可能会在图像还没有绘制完毕就返回

// img     将要绘制的图像

// x       左上角的 x 坐标

// y       左上角的 y 坐标

// observer    绘制进程中以通告为目的的对象可能为 null

boolean drawImage(Image img, int x, int y, int width, int height, ImageObserver observer);

// 绘制一副比例图像。系统按照比例将图像放入给定宽和高的区域。这个调用可能会在图像还没有绘制完毕就返回

// img     将要绘制的图像

// x       左上角的 x 坐标

// y       左上角的 y 坐标

// width   描述图像的宽度

// height  描述图像的高度

// observer    绘制进程中以通告为目的的对象可能为 null

void copyArea(int x, int y, int width, int height, int dx, int dy);

// 拷贝屏幕的一块区域

// x   原始区域左上角的 x 坐标

// y   原始区域左上角的 y 坐标

// width   原始区域的高度

// height  原始区域的宽度

// dx      原始区域到目标区域的水平距离

// dy      原始区域到目标区域的垂直距离

```