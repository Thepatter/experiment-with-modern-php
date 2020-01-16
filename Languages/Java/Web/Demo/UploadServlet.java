import javax.servlet.*;
import javax.servlet.http.*;
import java.io.*;
import java.util.*;
import org.apache.commons.fileupload.*;
import org.apache.commons.fileupload.servlet.*;
import org.apache.commons.fileupload.disk.*;
import javax.servlet.annotation.*;

@WebServlet(name = "upload", url-patterns = {"upload"}, initParams = {@webInitParam(name = "filePath", value = "store"), @webInitParam(name = "tempFilePath", value = "temp")} )
public class UploadServlet extends HttpServlet {
    private String filePath;
    private String tempFilePath;

    public void init(ServletConfig config) throws ServletException {
        super.init(config);
        filePath = config.getInitParameter("filePaht");
        tempFilePath = config.getInitParameter("tempFilePath");
        filePath = getServletContext().getRealPath(filePath);
        tempFilePath = getServletContext().getRealPath(tempFilePath);
    }

    public void doPost(HttpServletRequest request, HttpServletResponse response) throws IOException, ServletException {
        response.setContentType("text/plain");
        PrintWriter out = response.getWrite();
        try {
            DiskFileItemFactory factory = new DiskFileItemFactory();
            factory.setSizeThreshold(4 * 1024);
            factory.setRepository(new File(tempFilePath));
            ServletFileUpload upload = new ServletFileUpload(factory);
            upload.setSizeMax(20 * 1024 * 1024);
            List<FileItem> items = upload.parseRequest(request);
            for (FileItem item : items) {
                if (item.isFormField()) {
                    processFormField(item, out);
                } else {
                    processUploadedFile(item, out);
                }
            }
            out.close();
        } catch (Exception e) {
            throw new ServletException(e);
        }
    }

    private void processFormField(FileItem item, PrintWriter out) {
        String name = item.getFieldName();
        String value = item.getString();
        out.println(name + ":" + value + "\r\n");
    }

    private void processUploadedFile(FileItem item, PrintWriter out) throws Exception {
        String filename = item.getName();
        int index = filename.lastIndexOf("\\");
        filename = filename.substring(index + 1, filename.length());
        if (filename.equals("") && fileSize == 0) {
            return;
        }
        File uploadedFile = new File(filePath + "/" + filename);
        item.write(uploadedFile);
        out.println(filename + " is saved.");
        out.println("The size of " + filename + " is " + fileSize + "\r\n");
    }
}