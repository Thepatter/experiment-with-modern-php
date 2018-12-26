package Logging;

import java.awt.*;
import java.awt.event.*;
import java.io.*;
import java.util.logging.*;
import javax.swing.*;

public class LoggingImageViewer {
    public static void main(String[] args) {
        if (System.getProperty("java.util.logging.config.class") == null &&
            System.getProperty("java.util.logging.config.file") == null
        ) {
            try {
                Logger.getLogger("com.horstmann.corejava").setLevel(Level.ALL);
                final int LOG_ROTATION_COUNT = 10;
                Handler handler = new FileHandler("%h/LoggingImageViewer.log", 0, LOG_ROTATION_COUNT);
                Logger.getLogger("com.horstmann.corejava").addHandler(handler);
            } catch (IOException e) {
                Logger.getLogger("com.horstmann.corejava").log(Level.SEVERE, "Can't create log file handler", e);
            }
        }

        EventQueue.invokeLater(()->{
            Handler windowHandler = new WindowHandler();
            windowHandler.setLevel(Level.ALL);
            Logger.getLogger("com.horstmann.corejava").addHandler(windowHandler);
            JFrame frame = new ImageViewerFrame();
            frame.setTitle("LoggingImageViewer");
            frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
            Logger.getLogger("com.horstmann.corejava").fine("Showing frame");
            frame.setVisible(true);
        });
    }
}
