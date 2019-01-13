package streams;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Arrays;
import java.util.List;

/**
 * @author zyw
 */
public class CountLongWords {

    private static final int  WORD_LENGTH = 12;

    public static void main(String[] args) throws IOException {
        System.out.println(System.getProperty("user.dir"));
        System.out.println(System.getProperty("line.separator"));
        List<String> words = getContents();
        useIterator(words);
        useStream(words);
    }

    private static List<String> getContents() throws IOException
    {
        String contents = Files.readString(Paths.get("core_Java_second/src/streams/alice30.txt"));
        return Arrays.asList(contents.split(" "));
    }

    private static void useIterator(List<String> words)
    {
        long count = 0;
        for (String w: words) {
            if (w.length() > WORD_LENGTH) {
                count++;
            }
        }
        System.out.println("长度大于 " + WORD_LENGTH + " 的单词个数为：" + count);
    }

    private static void useStream(List<String> words) throws IOException
    {
        long count = words.stream().filter(w->w.length() > WORD_LENGTH).count();
        System.out.println("长度大于 " + WORD_LENGTH + " 的单词个数为：" + count);
    }


}
