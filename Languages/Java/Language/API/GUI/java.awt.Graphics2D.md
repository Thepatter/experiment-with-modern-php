*java.awt.Graphics2D*
```java
Paint getPaint();

void setPaint(Paint p);

// 获取或设置这个图形环境的绘制属性。`Color` 类实现了 `Paint` 接口。可以使用这个方法将绘制属性设置为纯色

void fill(Shape s);

// 用当前的颜色填充该图形

FontRenderContext getFontRenderContext();

// 返回这个图形文本中，指定字体特征的字体绘制环境

void drawString(String str, float x, float y);

// 采用当前的字体和颜色绘制一个字符串

// str 将要绘制的字符串

// x   字符串开始的 x 坐标

// y   字符串基线的 y 坐标

```