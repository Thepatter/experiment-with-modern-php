package streams;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.*;
import java.util.stream.Collectors;
import java.util.stream.Stream;

public class CollectingResults {

    public static void main(String[] args) throws IOException
    {
        Iterator<Integer> iterator = Stream.iterate(0, n -> n + 1).limit(10).iterator();
        while (iterator.hasNext()) {
            System.out.println(iterator.next());
        }
        Object[] numbers = Stream.iterate(0, n -> n + 1).limit(10).toArray();
        System.out.println("Object array:" + numbers);
        try {
            Integer number = (Integer) numbers[0];
            System.out.println("number: " + number);
            System.out.println("The following statement throws an exception:");
            Integer[] number2 = (Integer[]) numbers;
        } catch (ClassCastException ex) {
            ex.printStackTrace();
        }
        Integer[] numbers3 = Stream.iterate(0, n -> n + 1).limit(10).toArray(Integer[]::new);
        System.out.println("Integer array: " + numbers3);
        Set<String> noVowelSet = noVowels().collect(Collectors.toSet());
        show("noVowelSet", noVowelSet);
        TreeSet<String> noVowelTreeSet = noVowels().collect(Collectors.toCollection(TreeSet::new));
        show("noVowelTreeSet", noVowelTreeSet);
        String result = noVowels().limit(10).collect(Collectors.joining());
        System.out.println("Joining: " + result);
        result = noVowels().limit(10).collect(Collectors.joining(", "));
        System.out.println("Joining with commas: " + result);
        IntSummaryStatistics summaryStatistics = noVowels().collect(Collectors.summarizingInt(String::length));
        double averageWordLength = summaryStatistics.getAverage();
        double maxWordLength = summaryStatistics.getMax();
        System.out.println("Average word length: " + averageWordLength);
        System.out.println("Max word length: " + maxWordLength);
        System.out.println("forEach:");
        noVowels().limit(10).forEach(System.out::println);
    }

    private static Stream<String> noVowels() throws IOException
    {
        String contents = Files.readString(Paths.get("core_Java_second/src/streams/alice30.txt"));
        List<String> wordList = Arrays.asList(contents.split(" "));
        Stream<String> words = wordList.stream();
        return words.map(s -> s.replaceAll("[aeiouAEIOU]", "helloJava"));
    }

    private static <T> void show(String label, Set<T> set)
    {
        System.out.print(label + ": " + set.getClass().getName());
        System.out.println("[" + set.stream().limit(10).map(Object::toString).collect(Collectors.joining(", ")) + "]");
    }

}
