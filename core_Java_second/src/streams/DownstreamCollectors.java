package streams;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.*;
import java.util.stream.Stream;

import static java.util.stream.Collectors.*;

/**
 * @author zyw
 */
public class DownstreamCollectors {

    private static class City
    {
        private String name;
        private String state;
        private int population;

        City(String name, String state, int population)
        {
            this.name = name;
            this.state = state;
            this.population = population;
        }

        String getName()
        {
            return name;
        }

        String getState()
        {
            return state;
        }

        int getPopulation()
        {
            return population;
        }
    }

    private static Stream<City> readCities(String filename) throws IOException
    {
        return Files.lines(Paths.get(filename)).map(l -> l.split(", ")).map(a -> new City(a[0], a[1], Integer.parseInt(a[2])));
    }

    public static void main(String[] args) throws IOException
    {
        Stream<Locale> locales = Stream.of(Locale.getAvailableLocales());
        locales = Stream.of(Locale.getAvailableLocales());
        Map<String, Set<Locale>> countryToLocaleSet = locales.collect(groupingBy(Locale::getCountry, toSet()));
        System.out.println("countryToLocaleSet: " + countryToLocaleSet);

        locales = Stream.of(Locale.getAvailableLocales());
        Map<String, Long> countryToLocaleCounts = locales.collect(groupingBy(Locale::getCountry, counting()));
        System.out.println("countryToLocaleCounts: " + countryToLocaleCounts);

        Stream<City> cites = readCities("core_Java_second/src/streams/cities.txt");
        Map<String, Integer> stateToCityPopulation = cites.collect(groupingBy(City::getState, summingInt(City::getPopulation)));
        System.out.println("stateToCityPopulation: " + stateToCityPopulation);

        cites = readCities("core_Java_second/src/streams/cities.txt");
        Map<String, Optional<String>> stateToLongestCityName = cites.collect(groupingBy(City::getState, mapping(City::getName, maxBy(Comparator.comparing(String::length)))));
        System.out.println("stateToLongestCityName: " + stateToLongestCityName);

        locales = Stream.of(Locale.getAvailableLocales());
        Map<String, Set<String>> countryToLanguages = locales.collect(groupingBy(Locale::getDisplayCountry, mapping(Locale::getDisplayLanguage, toSet())));
        System.out.println("countryToLanguage: " + countryToLanguages);

        cites = readCities("core_Java_second/src/streams/cities.txt");
        Map<String, IntSummaryStatistics> stateToCityPopulationSummary = cites.collect(groupingBy(City::getState, summarizingInt(City::getPopulation)));
        System.out.println(stateToCityPopulationSummary.get("NY"));

        cites = readCities("core_Java_second/src/streams/cities.txt");
        Map<String, String> stateToCityNames = cites.collect(groupingBy(City::getState, reducing("", City::getName, (s,t )->s.length() == 0 ? t: s + ", " + t)));
        cites = readCities("core_Java_second/src/streams/cities.txt");
        stateToCityNames = cites.collect(groupingBy(City::getState, mapping(City::getName, joining(", "))));
        System.out.println("stateToCityNames: " + stateToCityNames);
    }
}
