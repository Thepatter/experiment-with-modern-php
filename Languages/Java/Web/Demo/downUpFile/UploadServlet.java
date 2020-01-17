package chapter4;

import org.apache.commons.fileupload.FileItem;
import org.apache.commons.fileupload.disk.DiskFileItemFactory;
import org.apache.commons.fileupload.servlet.ServletFileUpload;

import javax.servlet.ServletContext;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.io.File;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Iterator;
import java.util.List;

// 中文文件上传后会乱码
@WebServlet(urlPatterns = {"/up"})
public class UploadServlet extends HttpServlet {
    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        resp.setContentType("Application/json;charset=UTF-8");
        if (ServletFileUpload.isMultipartContent(req)) {
            DiskFileItemFactory factory = new DiskFileItemFactory();
            ServletContext servletContext = this.getServletConfig().getServletContext();
            String temDir = this.getServletConfig().getServletContext().getRealPath("temp");
            String saveDir = this.getServletConfig().getServletContext().getRealPath("store");
            File repository = (File) servletContext.getAttribute(temDir);
            factory.setRepository(repository);
            ServletFileUpload upload = new ServletFileUpload(factory);
            try {
                List<FileItem> items = upload.parseRequest(req);
                Iterator<FileItem> iter = items.iterator();
                while (iter.hasNext()) {
                    FileItem item = iter.next();
                    if (item.isFormField()) {
                        String name = item.getFieldName();
                        String value = item.getString();
                    } else {
                        String fieldName = item.getFieldName();
                        String fileName = item.getName();
                        fileName = fileName.substring(fileName.lastIndexOf("\\") + 1);
                        String contentType = item.getContentType();
                        long sizeInBytes = item.getSize();
                        File uploadFile = new File(saveDir + "/" + fileName);
                        item.write(uploadFile);
                        PrintWriter out = resp.getWriter();
                        StringBuilder stringBuilder = new StringBuilder();
                        out.printf("{\"fieldName\":\"%s\", \"fileName\": \"%s\", \"contentType\": \"%s\", \"size\": \"%d\"}", fieldName, fileName, contentType, sizeInBytes);
                        out.flush();
                    }
                }
            } catch (Exception e) {
                e.printStackTrace();
            }
        }
    }
}