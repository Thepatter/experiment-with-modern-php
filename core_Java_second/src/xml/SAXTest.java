package xml;

import org.xml.sax.Attributes;
import org.xml.sax.helpers.DefaultHandler;

import javax.xml.parsers.SAXParser;
import javax.xml.parsers.SAXParserFactory;
import java.io.InputStream;
import java.net.URL;

public class SAXTest {
    public static void main(String[] args) throws Exception
    {
        String url;
        if (args.length == 0) {
            url = "http://www.w3c.org";
            System.out.println("Using " + url);
        } else {
            url = args[0];
        }
        DefaultHandler handler = new DefaultHandler()
        {
            @Override
            public void startElement(String namespaceURI, String lname, String qname, Attributes attributes) {
                if (lname.equals("a") && attributes != null) {
                    for (int i = 0; i < attributes.getLength(); i++) {
                        String aname = attributes.getLocalName(i);
                        if (aname.equals("href")) {
                            System.out.println(attributes.getValue(i));
                        }
                    }
                }
            }
        };
        SAXParserFactory factory = SAXParserFactory.newInstance();
        factory.setNamespaceAware(true);
        factory.setFeature("http://apache.org/xml/feature/nonvalidating/load-external-dtd", false);
        SAXParser saxParser = factory.newSAXParser();
        InputStream in = new URL(url).openStream();
        saxParser.parse(in, handler);
    }
}
