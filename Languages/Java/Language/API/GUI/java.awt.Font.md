*java.awt.Font*
```java
Font(String name, int style, int size);

// 创建一个新字体对象

// name 字体名，style 字体风格（`Font.PLAIN`, `Font.BOLD`, `Font.ITALIC`, `Font.BOLD`, `Font.ITALIC`）

String getFontName();

// 返回字体名

String getFamily();

// 返回字体家族名

String getName();

// 如果采用逻辑字体名创建字体，将返回逻辑字体

Rectangle2D getStringBounds(String s, FontRenderContext context);

// 返回包围这个字符串的矩形。矩形的起点为基线。矩形顶端的 y 坐标等于上坡度的负值。矩形的高度等于上坡度，下坡度和行间距之和。宽度等于字符串的宽度

LineMetrics getLineMetrics(String s, FontRenderContext context);

// 返回测定字符串宽度的一个线性 `metrics` 对象

Font deriveFont(int style);

Font deriveFont(float size);

Font deriveFont(int style, float size);

// 返回一个新字体，除给定大小和字体风格外，其余与原字体一样

```