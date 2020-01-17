package chapter4;

import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.*;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

@WebServlet(urlPatterns = {"/down"})
public class DownloadServlet extends HttpServlet {
    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        String filename = req.getParameter("filename");
        if (filename != null) {
            try (InputStream in = getServletContext().getResourceAsStream("/store/" + filename)) {
                int length = in.available();
                resp.setContentType("application/force-download");
                resp.setHeader("Content-Length", String.valueOf(length));
                resp.setHeader("Content-Disposition", "attachment;filename=\"" + filename + "\" ");
                try (OutputStream out = resp.getOutputStream()) {
                    int bytesRead = 0;
                    byte[] buffer = new byte[512];
                    while ((bytesRead = in.read(buffer)) != -1) {
                        out.write(buffer, 0, bytesRead);
                    }
                }
            }
        }
    }
}
