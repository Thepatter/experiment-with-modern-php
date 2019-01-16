package streams;

import java.io.IOException;
import java.math.BigInteger;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.util.List;
import java.util.regex.Pattern;
import java.util.stream.Collectors;
import java.util.stream.Stream;

/**
 * @author zyw
 */
public class CreatingStreams {

    private static final String FILENAME = "core_Java_second/src/streams/alice30.txt";

    public static void main(String[] args) throws IOException {
        Path path = Paths.get(FILENAME);
        String contents = Files.readString(path);
        Stream<String> words = Stream.of(contents.split(" "));
        show("words", words);
        Stream<String> song = Stream.of("gently", "down", "the", "stream");
        show("song", song);
        Stream<String> silence = Stream.empty();
        show("silence", silence);
        Stream<String> echos = Stream.generate(()-> "Echo");
        show("echos", echos);
        Stream<Double> randoms = Stream.generate(Math::random);
        show("randoms", randoms);
        Stream<BigInteger> integers = Stream.iterate(BigInteger.ONE, n -> n.add(BigInteger.ONE));
        show("integers", integers);
        String pattern = " ";
        Stream<String> wordsAnotherWay = Pattern.compile(pattern).splitAsStream(contents);
        show("wordsAnotherWay", wordsAnotherWay);
        try (Stream<String> lines = Files.lines(path, StandardCharsets.UTF_8)) {
            show("lines", lines);
        }
    }

    private static<T> void show(String title, Stream<T> stream) {
        final int size = 10;
        List<T> firstElements = stream.limit(size + 1).collect(Collectors.toList());
        System.out.print(title + ": ");
        for (int i = 0; i < firstElements.size(); i++) {
            if (i > 0) {
                System.out.print(", ");
            }
            if (i < size) {
                System.out.print(firstElements.get(i));
            } else {
                System.out.print("...");
            }
        }
        System.out.println();
    }
}
