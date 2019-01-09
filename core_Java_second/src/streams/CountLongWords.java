package streams;

import java.io.IOException;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Arrays;
import java.util.List;

public class CountLongWords {

    private static final int  wordLength = 12;

    public static void main(String[] args) throws IOException {
        List<String> words = getContents();
        useIterator(words);
        useStream(words);
    }

    private static List<String> getContents() throws IOException
    {
//        String contents = new String(Files.readAllBytes(Paths.get("core_Java_second/src/streams/alice30.txt")), StandardCharsets.UTF_8);
        String contents = Files.readString(Paths.get("core_Java_second/src/streams/alice30.txt"));

        return Arrays.asList(contents.split(" "));
    }

    private static void useIterator(List<String> words)
    {
        long count = 0;
        for (String w: words) {
            if (w.length() > wordLength) {
                count++;
            }
        }
        System.out.println("长度大于 " + wordLength + " 的单词个数为：" + count);
    }

    private static void useStream(List<String> words) throws IOException
    {
        long count = words.stream().filter(w->w.length() > wordLength).count();
        System.out.println("长度大于 " + wordLength + " 的单词个数为：" + count);
    }


}
