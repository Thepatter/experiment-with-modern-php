import java.util.Date;
import java.util.GregorianCalendar;

public class EmployeeClone implements Cloneable {
    private String name;
    private double salary;
    private Date hireDay;

    public EmployeeClone(String name, double salary)
    {
        this.name = name;
        this.salary = salary;
        hireDay = new Date();
    }
    public Employee clone() throws CloneNotSupportedException
    {
        Employee cloned = (Employee) super.clone();
//        cloned.hireDay = (Date) hireDay.clone();
        return cloned;
    }

    public void setHireDay(int year, int month, int day)
    {
        Date newHireDay = new GregorianCalendar(year, month - 1 , day).getTime();
        hireDay.setTime(newHireDay.getTime());
    }

    public void raiseSalary(double byPercent)
    {
        double raise = salary * byPercent;
        salary += raise;
    }

    public String toString()
    {
        return "Employee[name=" + name + ",salary=" + salary + ",hireDay=" + hireDay + "]";
    }
}
