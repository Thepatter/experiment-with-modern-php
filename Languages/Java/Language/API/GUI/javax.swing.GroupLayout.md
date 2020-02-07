*javax.swing.GroupLayout*
```java
GroupLayout(Container host);

// 构造一个 `GroupLayout` 对象，用于布局 `host` 容器中的组件（host 容器仍然需要调用 `setLayout`）

void setHorizontalGroup(GroupLayout.Group g);

void setVerticalGroup(GroupLayout.Group g);

// 设置用于控制水平或垂直容器的组

void linkSize(Component... components);

void linkSize(int axis, Component... component);

// 强制给定的几个组件具有相同的尺寸，或者在指定的坐标轴上有相同的尺寸（`SwingConstants.HORIZONTAL` 或 `SwingConstants.VERTICAL`）

GroupLayout.SequentialGroup createSequentialGroup();

// 创建一个组，用于顺序地布局子组件

GroupLayout.ParallerGroup createParallelGroup();

GroupLayout.ParallelGroup createParallelGroup(GroupLayout.Alignment align);

GroupLayout.ParallelGroup createParallerlGroup(GroupLayout.Alignment align, boolean resizable);

// 创建一个组，用于并行地布局子组件

// align       `BASELINE`, `LEADING`（默认值），`TRAILING` 或 `CENTER`

// resizable   如果租可以调整大小，这个值为 true（默认值）；如果首选的尺寸是最小尺寸或最大尺寸，这个值为 false

boolean getHonorsvisibility();

void setHonorsvisibility(boolean b);

// 获取或设置 `honorsVisibility` 特性。当这个值为 `true` （默认值）时，不可见的组件不参与布局。当这个值为 `false` 时，好像可见的组件一样，这些组件也参与布局。这个特性主要用于想要临时隐藏一些组件，而又不希望改变布局的情况

boolean getAutoCreateCaps();

void setAutoCreateCaps(boolean b);

boolean getAutoCreateContainerCaps();

void setAutoCreateContainerCaps(boolean b);

// 获取或设置 `autoCreateCaps` 和 `autoCreateContainerCaps` 特性。当这个值为 `true` 时，将自动地在组件或容器边框之间添加空隙。默认值是 `false`。在手动构造 `GroupLayout` 时，true 值很有用

GroupLayout.Group addComponent(Component c);

GroupLayout.Group addComponent(Component c, int minimumSize, int preferredSize, int maximumSize);

// 添加一个组件至本组中。尺寸参数可以是一个实际值（非负值），或者是一个特定的常量 `GroupLayout.DEFAULT_SIZE` 或 `GroupLayout.PREFERRED_SIZE`，当使用 `DEFAULT_SIZE`, 将调用组件的 `getMinimumSize`,

// `getPreferredSize` 或 `getMaximumSize` 当使用 `PREFERRED_SIZE` 时，将调用组件的 `getPreferredSize` 方法

GroupLayout.Group addCap(int size);

GroupLayout.Group addCap(int minimumSize, int preferredSize, int maximumSize);

// 添加一个固定的或可调节的间隙

GroupLayout.Group addGroup(GroupLayout.Group g);

// 将一个给定的组添加到本组中

```