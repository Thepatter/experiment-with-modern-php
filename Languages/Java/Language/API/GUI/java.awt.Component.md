*java.awt.Component*
```java
boolean isVisible();

void setVisible(boolean b);

// 获取或设置 `visible` 属性。组件最初是可见的，但 `JFrame` 这样的顶层组件例外

void setSize(int width, int height);

// 使用给定的宽度和高度，重新设置组件的大小

void setLocation(int x, int y);

// 将组件移到一个新的位置上。如果这个组件不是顶层组件，`x` 和 `y` 坐标（或者 `p.x` 和 `p.y`）是容器坐标；否则是屏幕坐标

void setBounds(int x, int y, int width, int height);

// 移到并重新设置组件的大小

// x, y 组件新的左上角位置

// width, height   组件新的尺寸

Dimension getSize();

void setSize(Dimension d);

// 获取或设置当前组件的 size 属性

void repaint();

// 尽可能快的重新绘制组件

Dimension getPreferredSize();

// 要覆盖这个方法，返回这个组件的首选大小

Color getBackground();

void setBackground(Color c);

// 获取或设置背景颜色，c 新背景颜色

Color getForeground();

void setForeground(Color c);

// 获取或设置前景颜色，c 新前景颜色

void setCursor(Cursor cursor);

// 用光标图像设置给定光标

void validate();

// 重新计算组件的位置和大小。如果组件是容器，容器中包含的所有组件的位置和大小也被重新计算

Font getFont();

// 获取组件的字体

```