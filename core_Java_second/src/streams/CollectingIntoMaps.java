package streams;

import java.io.IOException;
import java.util.*;
import java.util.function.Function;
import java.util.stream.Collectors;
import java.util.stream.Stream;

public class CollectingIntoMaps {
    private static class Person
    {
        private int id;
        private String name;

        Person(int id, String name)
        {
            this.id = id;
            this.name = name;
        }
        int getId() {
            return id;
        }

        String getName() {
            return name;
        }

        /**
         * @Override
         */
        public String toString()
        {
            return getClass().getName() + "{id=" + id + ",name=" + name +"}";
        }

    }

    private static Stream<Person> people()
    {
        return Stream.of(new Person(1001, "Peter"), new Person(1002, "Paul"), new Person(1003, "Mary"));
    }

    public static void main(String[] args) throws IOException
    {
        Map<Integer, String> idToName = people().collect(Collectors.toMap(Person::getId, Person::getName));
        System.out.println("idToName: " + idToName);

        Map<Integer, Person> idToPerson = people().collect(Collectors.toMap(Person::getId, Function.identity()));
        System.out.println("idToPerson: " + idToPerson.getClass().getName() + idToPerson);
        idToPerson = people().collect(
                Collectors.toMap(Person::getId, Function.identity(), (
                        existingValue, newValue)->{
                            throw new IllegalStateException();
                }, TreeMap::new));
        System.out.println("idToPerson: " + idToPerson.getClass().getName() + idToPerson);

        Stream<Locale> locales = Stream.of(Locale.getAvailableLocales());
        Map<String, String> languageNames = locales.collect(
                Collectors.toMap(
                        Locale::getDisplayLanguage,
                        l -> l.getDisplayLanguage(l),
                        (existingValue, newValue) -> existingValue
                )
        );
        System.out.println("languageNames: " + languageNames);

        locales = Stream.of(Locale.getAvailableLocales());

        Map<String, Set<String>> countryLanguageSets = locales.collect(
                Collectors.toMap(
                        Locale::getDisplayCountry,
                        l -> Collections.singleton(l.getDisplayLanguage()),
                        (a, b) -> {
                            Set<String> union = new HashSet<>(a);
                            union.addAll(b);
                            return union;
                        }
                )
        );
        System.out.println("countryLanguageSets: " + countryLanguageSets);
    }
}
