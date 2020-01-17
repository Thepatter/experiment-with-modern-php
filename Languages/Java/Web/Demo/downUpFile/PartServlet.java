package chapter4;

import javax.servlet.ServletException;
import javax.servlet.annotation.MultipartConfig;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.Part;
import java.io.File;
import java.io.IOException;
import java.io.PrintWriter;
import java.util.Collection;

// 中文文件名不会乱码
@WebServlet(urlPatterns = {"/upPart"})
@MultipartConfig
public class PartServlet extends HttpServlet {
    @Override
    protected void doPost(HttpServletRequest req, HttpServletResponse resp) throws ServletException, IOException {
        resp.setContentType("Application/json;charset=UTF-8");
        try (PrintWriter out = resp.getWriter()) {
            String savePath = this.getServletConfig().getServletContext().getRealPath("store");
            StringBuilder stringBuilder = new StringBuilder();
            stringBuilder.append("{");
            appendJson("savePath", savePath, stringBuilder);
            stringBuilder.append("\"parts\":[");
            Collection<Part> parts = req.getParts();
            for (Part part : parts) {
                stringBuilder.append("{");
                String header = part.getHeader("content-disposition");
                String fieldContentType = part.getContentType();
                appendJson("filedContentType", fieldContentType, stringBuilder);
                long fieldSize = part.getSize();
                appendJson("fieldSize", String.valueOf(fieldSize), stringBuilder);
                String fieldName = part.getName();
                appendJson("fieldName", fieldName, stringBuilder);
                if (fieldContentType == null) {
                    String fieldValue = req.getParameter(fieldName);
                    appendJson("fieldValue", fieldValue, stringBuilder);
                } else {
                    String fileName =header.substring(header.lastIndexOf("=") + 2, header.length() - 1);
                    appendJson("fileName", fileName, stringBuilder);
                    part.write(savePath + File.separator + fileName);
                }
                stringBuilder.append("\"part\": \"end\"");
                stringBuilder.append("},");
            }
            stringBuilder.setCharAt(stringBuilder.length() - 1, ']');
            stringBuilder.append("}");
            out.println(stringBuilder.toString());
        }
    }

    private void appendJson(String key, String value, StringBuilder stringBuilder) {
        stringBuilder.append("\"").append(key).append("\": ").append("\"").append(value).append("\"").append(",");
    }
}
