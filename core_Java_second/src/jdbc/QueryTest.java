package jdbc;

import java.io.IOException;
import java.io.InputStream;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.sql.*;
import java.util.ArrayList;
import java.util.List;
import java.util.Properties;
import java.util.Scanner;

/**
 * @author zyw
 */
public class QueryTest {
    private static final String allQuery = "SELECT Books.price, Books.Title FROM Books";

    private static final String authorPublisherQuery = "SELECT Books.Price, Books.Title"
            + " FROM Books, BooksAuthors, Authors, Publishers"
            + " WHERE Authors.Author_Id = BooksAuthors.Author_Id AND BooksAuthors.ISBN = Books.ISBN"
            + " AND Books.Publisher_Id = Publishers.Publisher_Id AND Authors.Name = ?"
            + " AND Publishers.Name = ?";

    private static final String authorQuery
            = "SELECT Books.Price, Books.Title FROM Books, BooksAuthors, Authors"
            + " WHERE Authors.Author_Id = BooksAuthors.Author_Id AND BooksAuthors.ISBN = Books.ISBN"
            + " AND Authors.Name = ?";

    private static final String publisherQuery
            = "SELECT Books.Price, Books.Title FROM Books, Publishers"
            + " WHERE Books.Publisher_Id = Publishers.Publisher_Id AND Publishers.Name = ?";


    private static final String priceUpdate = "UPDATE Books " + "SET Price = Price + ? "
            + " WHERE Books.Publisher_Id = (SELECT Publisher_Id FROM Publishers WHERE Name = ?)";

    private static Scanner in;
    private static ArrayList<String> authors = new ArrayList<>();
    private static ArrayList<String> publishers = new ArrayList<>();

    public static void main(String[] args) throws IOException
    {
        try (Connection connection = getConnection()) {
            in = new Scanner(System.in);
            authors.add("Any");
            publishers.add("Any");
            try (Statement statement = connection.createStatement()) {
                String query = "SELECT Name FROM Authors";
                try (ResultSet rs = statement.executeQuery(query)) {
                    while (rs.next()) {
                        authors.add(rs.getString(1));
                    }
                }
                query = "SELECT Name FROM Publishers";
                try (ResultSet rs = statement.executeQuery(query)) {
                    while (rs.next()) {
                        publishers.add(rs.getString(1));
                    }
                }
            }
            boolean done = false;
            while (!done) {
                System.out.print("Query Change price Exit: ");
                String input = in.next().toUpperCase();
                if (input.equals("Q")) {
                    executeQuery(connection);
                } else if (input.equals("C")) {
                    changePrices(connection);
                } else {
                    done = true;
                }
            }
        } catch (SQLException e) {
            for (Throwable t : e) {
                System.out.println(t.getMessage());
            }
        }
    }

    private static void executeQuery(Connection connection) throws SQLException
    {
        String author = select("Authors: ", authors);
        String publisher = select("Publishers: ", publishers);
        PreparedStatement statement;
        if (!author.equals("Any") && !publisher.equals("Any")) {
            statement = connection.prepareStatement(authorPublisherQuery);
            statement.setString(1, author);
            statement.setString(2, publisher);
        } else if (!author.equals("Any") && publisher.equals("Any")) {
            statement = connection.prepareStatement(authorQuery);
            statement.setString(1, author);
        } else if (author.equals("Any") && !publisher.equals("Any")) {
            statement = connection.prepareStatement(publisherQuery);
            statement.setString(1, publisher);
        } else {
            statement = connection.prepareStatement(allQuery);
        }
        try (ResultSet resultSet = statement.executeQuery()) {
            while (resultSet.next()) {
                System.out.println(resultSet.getString(1) + ", " + resultSet.getString(2));
            }
        }
    }

    private static void changePrices(Connection connection) throws SQLException
    {
        String publisher = select("Publisher: ", publishers.subList(1, publishers.size()));
        System.out.print("Change price by: ");
        double priceChange = in.nextDouble();
        PreparedStatement statement = connection.prepareStatement(priceUpdate);
        statement.setDouble(1, priceChange);
        statement.setString(2, publisher);
        int r = statement.executeUpdate();
        System.out.println(r + " records updated.");
    }

    private static String select(String prompt, List<String> options) {
        while (true) {
            System.out.println(prompt);
            for (int i = 0; i < options.size(); i++) {
                System.out.printf("%2d) %s%n", i + 1, options.get(i));
            }
            int sel = in.nextInt();
            if (sel > 0 && sel <= options.size()) {
                return options.get(sel - 1);
            }
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
        }
        String url = properties.getProperty("jdbc.url");
        String username = properties.getProperty("jdbc.username");
        String password = properties.getProperty("jdbc.password");
        return DriverManager.getConnection(url, username, password);
    }
}
