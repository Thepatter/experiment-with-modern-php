*javax.swing.BorderFactory*
```java
static Border createLineBorder(Color color);

static Border createLineBorder(Color color, int thickness);

// 创建一个简单的直线边框

static MatteBorder createMatteBorder(int top, int left, int bottom, int right, Color color);

static MatteBorder createMatteBorder(int top, int left, int bottom, int right, Icon tileIcon);

// 创建一个用 `color` 颜色或一个重复图标填充的粗的边框

static Border createEmptyBorder();

static Border createEtchedBorder(Color highlight, Color shadow);

static Border createEtchedBorder(int type);

static Border createEtchedBorder(int type, Color highlight, Color shadow);

// 创建一个具有 3D 效果的直线边框

// highlight, shadow 用于 3D 效果的颜色

// type    `EtchedBorder.RAISED` 和 `EtchedBorder.LOWERED`

static Border createBevelBorder(int type);

static Border createBevelBorder(int type, Color highlight, Color shadow);

static Border createLoweredBevelBorder();

static Border createRaisedBevelBorder();

// 创建一个具有凹面或凸面效果的边框

// type    `BevelBorder.LOWERED` 或 `BevelBorder.RAISED`

// highlight, shadow           用于 3D 效果的颜色

static TitledBorder createTitledBorder(String title);

static TitledBorder createTitledBorder(Border border);

static TitledBorder createTitleBorder(Border border, String title);

static TitledBorder createTitledBorder(Border border, String title, int justification, int position);

static TitledBorder createTitleBorder(Border border, String title, int justification, int position, Font font);

static TitledBorder createdTitleBorder(Border border, String title, int justification, int position, Font font, Color color);

// 创建一个具有给定特性的带标题的边框

// title               标题字符串

// border              用标题装饰的边框

// justification       `TitleBorder` 常量 `LEFT`, `CENTER`, `RIGHT`, `LEADING`, `TRAILING`, `DEFAULT_JUSTIFICATION(left)` 之一

// position            `TitleBorder` 产量 `ABOVE_TOP`, `TOP`, `BELOW_TOP`, `ABOVE_BOTTOM`, `BOTTOM`, `BELOW_BOTTOM`, `DEFAULT_POSITION(top)` 之一

// font                标题的字体

// color               标题的颜色

static CompoundBorder createCompundBorder(Border outsideBorder, Border insideBorder);

// 将两个边框组合成一个新的边框

```