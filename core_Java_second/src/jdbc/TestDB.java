package jdbc;

import java.io.IOException;
import java.io.InputStream;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.*;
import java.util.Properties;

/**
 * @author zyw
 */
public class TestDB {
    public static void main(String[] args) throws IOException {
        try {
            runTest();
        } catch (SQLException ex) {
            for (Throwable t: ex) {
                t.printStackTrace();
            }
        }
    }

    private static void runTest() throws SQLException, IOException {
        try (Connection conn = getConnection();
             Statement stat = conn.createStatement())
        {
            stat.executeUpdate("CREATE TABLE Greetings (Message CHAR(20))");
            stat.executeUpdate("INSERT INTO Greetings VALUES ('Hello, World!')");

            try (ResultSet result = stat.executeQuery("SELECT * FROM Greetings"))
            {
                if (result.next())
                    System.out.println(result.getString(1));
            }
            stat.executeUpdate("DROP TABLE Greetings");
        }
    }

    private static Connection getConnection() throws SQLException, IOException {
        Properties properties = new Properties();
        try (InputStream in = Files.newInputStream(Paths.get("core_Java_second/src/jdbc/database.properties"))) {
            properties.load(in);
        }
        String drivers = properties.getProperty("jdbc.drivers");
        if (drivers != null) {
            System.setProperty("jdbc.drivers", drivers);
            String url = properties.getProperty("jdbc.url");
            String username = properties.getProperty("jdbc.username");
            String password = properties.getProperty("jdbc.password");
        }
        return DriverManager.getConnection(url, username, password);
    }
}
