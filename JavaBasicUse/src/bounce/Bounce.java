package bounce;

import java.awt.*;
import javax.swing.*;

public class Bounce {
    public static void main(String[] args) {
        EventQueue.invokeLater(()->{
            JFrame frame = new BounceFrame();
            frame.setTitle("BounceThread");
            frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
            frame.setVisible(true);
        });
    }
}
