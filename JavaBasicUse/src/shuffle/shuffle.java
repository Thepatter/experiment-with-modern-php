package shuffle;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;
import java.util.Random;

public class shuffle {
    public static void main(String[] args) {
        List<Integer> numbers = new ArrayList<>();
        for (int i = 1; i <= 49; i++) {
            numbers.add(i);
        }
        Collections.shuffle(numbers);
        List<Integer> winningCombination = numbers.subList(0, 6);
        Collections.sort(winningCombination);
        System.out.println(winningCombination);
        List<Double> doubles = new ArrayList<>();
        for (int i = 1; i <= 49; i++) {
            doubles.add(Math.random() * 100);
        }
        List<Double> winningCombinations = doubles.subList(1, 7);
        Collections.sort(winningCombinations);
        System.out.println(winningCombinations);
    }
}
