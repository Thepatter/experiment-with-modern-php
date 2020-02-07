*java.swing.JSlider*
```java
JSlider();

JSlider(int direction);

JSlider(int min, int max);

JSlider(int min, int max, int initialValue);

JSlider(int direction, int min, int max, int initialValue);

// 用给定的方向、最大值、最小值和初始化值构造一个水平滑动条

// direction       `SwingConstants.HORIZONTAL` 或 `SwingConstants.VERTICAL`

// min, max        滑动条的最大值，最小值。默认值为 0 - 100

// initialValue    滑动条的初始化值。默认值为 50

void setPaintTicks(boolean b);

// 如果 b 为 true，显示标尺

void setMajorTickSpacing(int units);

void setMinorTickSpacing(int units);

// 用给定的滑动条单位的倍数设置大标尺和小标尺

void setPaintLabels(boolean b);

// 如果 b 是 true，显示标尺标签

void setLableTable(Dictionary table);

// 设置用于作为标尺标签的组件，表中的每一个键/值对都采用 `new Integer(value)/component` 的格式

void setSnapToTicks(boolean b);

// 如果 b 是 true，每一次调整后滑块都要对齐到最接近的标尺处

void setPaintTrack(boolean b);

// 如果 b 是 true，显示滑动条滑动的轨迹

```