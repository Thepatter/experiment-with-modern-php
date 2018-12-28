package JCF;

import java.util.*;
import java.time.*;

public class PriorityQueueTest {
    public static void main(String[] args) {
        PriorityQueue<LocalDate> pd = new PriorityQueue<>();
        pd.add(LocalDate.of(1906, 12, 9));
        pd.add(LocalDate.of(1815, 12, 10));
        pd.add(LocalDate.of(1903, 12, 3));
        pd.add(LocalDate.of(1910, 6, 22));
        System.out.println("Iterating over elements...");
        for (LocalDate date: pd) {
            System.out.println(date);
        }
        System.out.println("Removing element...");
        while (!pd.isEmpty()) {
            System.out.println(pd.remove());
        }
    }
}
