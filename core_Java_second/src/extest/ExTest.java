package extest;

/**
 * @author zyw
 */
public class ExTest {
    public static void main(String[] args) {
        // 顺序执行
        System.out.println(finalTest());
        // 返回 try 中的值
        System.out.println(tryReturn());
        //
//        System.out.println(catchReturn());
//        System.out.println(finallyReturn());
    }
    private static String finalTest()
    {
        try {
            System.out.println("this code is try");
            return "this return is try";
        } catch (Exception ex) {
            System.out.println("this code is catch");
            System.out.println(ex.getMessage());
        } finally {
            System.out.println("this code is finally");
        }
        return "this return is last";
    }

    private static String tryReturn()
    {
        try {
            throw new Exception("hello");
//            return "this code is try statement return";
        } catch (Exception ex) {
            System.out.println(ex.getMessage());
            System.out.println("this code statement catch");
        } finally {
            System.out.println("this code is finally");
        }
        return "this return is last";
    }

    private static String catchReturn()
    {
        try {
            throw new Exception("this exception is try");
        } catch (Exception ex) {
            System.out.println("this print in catch statement");
            return ex.getMessage();
        } finally {
            System.out.println("this print in finally");
        }
//        return "this last return";
    }

    private static String finallyReturn()
    {
        try {
            System.out.println("in try");
            return "try return";
        } catch (Exception e) {
            e.getMessage();
        } finally {
            System.out.println("this in finally");
        }
        return "last return";
    }
}
