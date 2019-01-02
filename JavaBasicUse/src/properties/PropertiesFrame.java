package properties;

import javax.swing.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.*;
import java.util.Properties;

class PropertiesFrame extends JFrame {
    private static final int DEFAULT_WIDTH = 300;
    private static final int DEFAULT_HEIGHT = 300;
    private File propertiesFile;
    private Properties settings;
    PropertiesFrame()
    {
        String userDir = System.getProperty("user.home");
        File propertiesDir = new File(userDir, ".corejava");
        if (!propertiesDir.exists()) {
            propertiesDir.mkdir();
        }
        propertiesFile = new File(propertiesDir, "program.properties");
        Properties defaultSettings = new Properties();
        defaultSettings.setProperty("left", "0");
        defaultSettings.setProperty("top", "0");
        defaultSettings.setProperty("width", "" + DEFAULT_WIDTH);
        defaultSettings.setProperty("height", Integer.toString(DEFAULT_HEIGHT));
        defaultSettings.setProperty("title", "");
        settings = new Properties(defaultSettings);
        if (propertiesFile.exists()) {
            try (InputStream in = new FileInputStream(propertiesFile)) {
                settings.load(in);
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
        int left = Integer.parseInt(settings.getProperty("left"));
        int top = Integer.parseInt(settings.getProperty("top"));
        int width = Integer.parseInt(settings.getProperty("width"));
        int height = Integer.parseInt(settings.getProperty("height"));
        setBounds(left, top, width, height);
        String title = settings.getProperty("title");
//        System.out.println(title);
//        System.exit(0);
        if (title.equals("")) {
            title = JOptionPane.showInputDialog("Please supply a frame title");
        }
        if (title == null) {
            title = "";
        }
        setTitle(title);
        addWindowListener(new WindowAdapter(){
            public void windowClosing(WindowEvent event) {
                settings.setProperty("left", "" + getX());
                settings.setProperty("top", "" + getY());
                settings.setProperty("width", "" + getWidth());
                settings.setProperty("height", "" + getHeight());
                try (OutputStream out = new FileOutputStream(propertiesFile)) {
                    settings.store(out, "Program Properties");
                } catch (IOException ex) {
                    ex.printStackTrace();
                }
                System.exit(0);
            }
        });
    }
}
