package intelnet;

import java.io.*;
import java.net.ServerSocket;
import java.net.Socket;
import java.nio.charset.StandardCharsets;
import java.util.Scanner;

/**
 * @author zyw
 */
public class MultipleEchoServer
{
    public static void main(String[] args) {
        try (ServerSocket serverSocket = new ServerSocket(8188)) {
            int i = 1;

            while (true) {
                Socket incoming = serverSocket.accept();
                System.out.println("Spawning " + i);
                Runnable r = new ThreadEchoHandler(incoming);
                Thread t = new Thread(r);
                t.start();
                i++;
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    static class ThreadEchoHandler implements Runnable
    {
        private Socket incoming;

        ThreadEchoHandler(Socket incomingSocket) {
            incoming = incomingSocket;
        }
        @Override
        public void run()
        {
            try (InputStream inputStream = incoming.getInputStream(); OutputStream outputStream = incoming.getOutputStream()) {
                Scanner in = new Scanner(inputStream, StandardCharsets.UTF_8);
                PrintWriter out = new PrintWriter(new OutputStreamWriter(outputStream, StandardCharsets.UTF_8), true);
                boolean done = false;
                while (!done && in.hasNextLine()) {
                    String line = in.nextLine();
                    out.println("Echo: " + line);
                    if (line.trim().equals("BYE")) {
                        done = true;
                    }
                }
            } catch (IOException e) {
                e.printStackTrace();
            }
        }
    }
}