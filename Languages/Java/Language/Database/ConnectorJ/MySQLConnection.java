import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.SQLTimeoutException;
import java.sql.Connection;

class MySQLConnection {

    static {
        try {
            Class.forName("com.mysql.cj.jdbc.Driver");
        } catch (ClassNotFoundException e) {
            e.printStackTrace();
        }
    }

    private static Connection connection = null;

    public static Connection getConnection() {
        if (connection == null) {
            try {
                connection = DriverManager.getConnection("jdbc://mysql://localhost/test?", "root", "secret");
                return connection;
            } catch (SQLException ex) {
                ex.printStackTrace();
            }
        }
        return null;
    }

    private MySQLConnection() {
    }
}