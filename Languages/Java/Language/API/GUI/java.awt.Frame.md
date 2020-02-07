*java.awt.Frame*
```java
boolean isResizable();

void setResizable(boolean b);

// 获取或设置 `resizable` 属性。这个属性设置后，用户可以重新设置框架的大小

String getTitle();

void setTitle(String s);

// 获取和设置 `title` 属性，这个属性确定框架标题栏中的文字

Image getIconImage();

void setIconImage(Image image);

// 获取或设置 `iconImage` 属性，这个属性确定框架的图标。窗口系统可能会将图标作为框架装饰或其他部位的一部分显示

boolean isUndecorated();

void setUndecorated(boolean b);

// 获取或设置 `undecorated` 属性。这个属性设置后，框架显示中将没有标题栏或关闭按钮这样的装饰。在框架显示之前，必须调用这个方法

int getExtendedState();

void setExtendedState(int state);

// 获取或设置窗口状态。状态是下列值之一 `Frame.NORMAL`, `Frame.ICONIFIED`, `Frame.MAXIMIZED_HORIZ`, `Frame.MAXIMIZED_VERT`,  `Frame.MAXIMIZED_BOTH`

```