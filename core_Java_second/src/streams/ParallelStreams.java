package streams;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.Arrays;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

import static java.util.stream.Collectors.*;

/**
 * @author zyw
 */
public class ParallelStreams {
    public static void main(String[] args) throws IOException
    {
        String contents = Files.readString(Paths.get("core_Java_second/src/streams/alice30.txt"));
        List<String> wordList = Arrays.asList(contents.split("\\PL+"));
        int[] shortWords = new int[10];
        System.out.println(shortWords.length);
        wordList.parallelStream().forEach(s ->
        {
            if (s.length() < shortWords.length) {
                shortWords[s.length()]++;
            }
        });
        System.out.println(Arrays.toString(shortWords));

        Map<Integer, Long> shortWordCounts = wordList.parallelStream().filter(
                s->s.length() < shortWords.length
        ).collect(groupingBy(String::length, counting()));
        System.out.println(shortWordCounts);

        Map<Integer, List<String>> result = wordList.parallelStream().collect(Collectors.groupingByConcurrent(String::length));

        System.out.println(result.get(14));

        result = wordList.parallelStream().collect(Collectors.groupingByConcurrent(String::length));
        System.out.println(result.get(14));

        Map<Integer, Long> wordCounts = wordList.parallelStream().collect(groupingByConcurrent(String::length, counting()));

        System.out.println(wordCounts);
    }
}
