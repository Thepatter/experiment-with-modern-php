package xml;

import javax.swing.*;
import java.awt.*;

public class XMLWriteTest {
    public static void main(String[] args) {
        EventQueue.invokeLater(()->{
            JFrame frame = new XMLWriteFrame();
            frame.setTitle("XMLWriteTest");
            frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
            frame.setVisible(true);
        });
    }
}
