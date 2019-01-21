package intelnet;

import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;
import java.nio.charset.StandardCharsets;
import java.util.Scanner;

/**
 * @author zyw
 */
public class SingleEchoServer {
    public static void main(String[] args) throws IOException {
        try (ServerSocket server = new ServerSocket(8189)) {
            try (Socket incoming = server.accept()) {
                InputStream inputStream = incoming.getInputStream();
                OutputStream outputStream = incoming.getOutputStream();
                try (Scanner in = new Scanner(inputStream, StandardCharsets.UTF_8)) {
                    PrintWriter out = new PrintWriter(new OutputStreamWriter(outputStream, StandardCharsets.UTF_8), true);
                    out.println("Hello! Enter BYE to exit");
                    boolean done = false;
                    while (!done && in.hasNextLine()) {
                        String line = in.nextLine();
                        out.println("Echo: " + line);
                        if (line.trim().equals("BYE")) {
                            done = true;
                        }
                    }
                }
            }
        }
    }
}
