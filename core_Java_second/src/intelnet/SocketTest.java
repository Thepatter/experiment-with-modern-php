package intelnet;

import java.io.IOException;
import java.net.Socket;
import java.util.Scanner;

/**
 * @author zyw
 */
public class SocketTest {
    public static void main(String[] args) throws IOException {
        try (Socket s = new Socket("time-a.nist.gov", 13);
            Scanner in = new Scanner(s.getInputStream(), "UTF-8")) {
            s.setSoTimeout(2000);
            while (in.hasNextLine()) {
                String line = in.nextLine();
                System.out.println(line);
            }
        }
    }
}
