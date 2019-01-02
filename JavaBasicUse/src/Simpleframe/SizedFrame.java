package Simpleframe;

import javax.swing.*;
import java.awt.*;

public class SizedFrame extends JFrame {
    Toolkit kit = Toolkit.getDefaultToolkit();
    Dimension screenSize = kit.getScreenSize();
    int screenHeight = screenSize.height;
    int screenWidth = screenSize.width;

}
