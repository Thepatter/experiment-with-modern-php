package streams;

import java.io.IOException;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.IntSummaryStatistics;
import java.util.Optional;
import java.util.OptionalInt;
import java.util.Random;
import java.util.stream.Collector;
import java.util.stream.Collectors;
import java.util.stream.IntStream;
import java.util.stream.Stream;

/**
 * @author zyw
 */
public class PrimitiveTypeStreams {
    public static void main(String[] args) throws IOException {
        System.out.println(Math.random());
        IntStream is1 = IntStream.generate(() -> (new Random()).nextInt());
        show("is1", is1);
        IntStream is2 = IntStream.range(5, 10);
        show("is2", is2);
        IntStream is3 = IntStream.rangeClosed(5, 10);
        show("is3", is3);
        Path path  = Paths.get("core_Java_second/src/streams/alice30.txt");
        String contents = Files.readString(path);
        Stream<String> words = Stream.of(contents.split("\\PL+"));
        IntStream is4 = words.mapToInt(String::length);
        show("is4", is4);
        String sentence = "\uD835\uDD46 is the set of octonions.";
        System.out.println(sentence);
        IntStream codes = sentence.codePoints();
        System.out.println(codes.mapToObj(c -> String.format("%X ", c)).collect(Collectors.joining()));
        Stream<Integer> integers = IntStream.range(0, 100).boxed();
        IntStream is5 = integers.mapToInt(Integer::intValue);
        show("is5", is5);
        OptionalInt maxIs6 = IntStream.of(12, 15, 29, 39, 112, 339).max();
        IntSummaryStatistics is6IntSummary = IntStream.generate(() -> (new Random().nextInt(100))).limit(10).summaryStatistics();
        System.out.println(maxIs6);
        System.out.println(is6IntSummary.getMax());
        System.out.println(is6IntSummary.getMin());
        System.out.println(is6IntSummary.getAverage());
        System.out.println(is6IntSummary.getSum());
    }

    private static void show(String title, IntStream stream)
    {
        final int size = 10;
        int[] firstElements = stream.limit(size + 1).toArray();
        System.out.print(title + ": ");
        for (int i = 0; i < firstElements.length; i++) {
            if (i > 0) {
                System.out.print(", ");
            }
            if (i < size) {
                System.out.print(firstElements[i]);
            } else {
                System.out.print("...");
            }
        }
        System.out.println();
    }
}
