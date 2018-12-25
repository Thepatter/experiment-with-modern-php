import java.util.*;
import java.lang.reflect.*;

public class ReflectionTest {

    public static void main(String[] args) {
        String name;
        if (args.length > 0) {
            name = args[0];
        } else {
            Scanner in = new Scanner(System.in);
            System.out.println("Enter class name (e.g. java.util.Date): ");
            name = in.next();
        }
        try {
            Class aClass = Class.forName(name);
            Class aSuperclass = aClass.getSuperclass();
            String aModifiers = Modifier.toString(aClass.getModifiers());
            if (aModifiers.length() > 0) {
                System.out.print(aModifiers + " ");
            }
            System.out.print("class " + name);
            if (aSuperclass != null && aSuperclass != Object.class) {
                System.out.print(" extends " + aSuperclass.getName());
            }
            System.out.print("\n{\n");
            printConstructors(aClass);
            System.out.println();
            printMethods(aClass);
            System.out.println();
            printFields(aClass);
            System.out.println("}");
        } catch (Exception e) {
            e.printStackTrace();
        }
        System.exit(0);
    }

    private static void printConstructors(Class cl)
    {
        Constructor[] constructors = cl.getDeclaredConstructors();
        for (Constructor c: constructors) {
            String name = c.getName();
            printReflectionInfo(c);
            System.out.print(name + "(");
            Class[] paramTypes = c.getParameterTypes();
            for (int j = 0; j < paramTypes.length; j++) {
                if (j > 0) {
                    System.out.print(", ");
                    System.out.print(paramTypes[j].getName());
                }
            }
            System.out.println(");");
        }
    }

    private static void printMethods(Class cl)
    {
        Method[] methods = cl.getDeclaredMethods();
        for (Method m: methods) {
            Class retType = m.getReturnType();
            String name = m.getName();
            printReflectionInfo(m);
            System.out.print(retType.getName() + " " + name + "(");
            Class[] paramTypes = m.getParameterTypes();
            for (int j = 0; j < paramTypes.length; j++) {
                if (j > 0) {
                    System.out.print(", ");
                }
                System.out.print(paramTypes[j].getName());
            }
            System.out.println(");");
        }
    }

    private static void printFields(Class cl)
    {
        Field[] fields = cl.getDeclaredFields();
        for (Field f: fields) {
            Class type = f.getType();
            String name = f.getName();
            printReflectionInfo(f);
            System.out.println(type.getName() + " " + name + ";");
        }
    }

    private static void printReflectionInfo(Member member)
    {
        System.out.print("    ");
        String modifiers = Modifier.toString(member.getModifiers());
        if (modifiers.length() > 0) {
            System.out.print(modifiers + " ");
        }
    }

}
