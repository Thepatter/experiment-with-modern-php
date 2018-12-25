import java.lang.reflect.Field;

public class Welcome {
    public static void main(String[] args) {

        String greeting = "Welcome to Core Java";

        System.out.println(greeting);

        for (int i = 0; i < greeting.length(); i++) {
            System.out.print("=");
        }
        System.out.println();
        try {
            Employee harry = new Employee("Harry Hacker", 35000, 1990, 1, 19);

            Class cl = harry.getClass();

            Field f = cl.getDeclaredField("name");
            f.setAccessible(true);
            Object v = f.get(harry);

        } catch (Exception e) {
            e.printStackTrace();
        }
    }
}
