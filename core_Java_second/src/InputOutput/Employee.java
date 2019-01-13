package InputOutput;

import java.time.LocalDate;

/**
 * @author zyw
 */
class Employee {

    static final int NAME_SIZE = 40;
    static final int RECORD_SIZE = 2 * NAME_SIZE + 8 + 4 + 4 + 4;

    private String name;
    private double salary;
    private LocalDate hireDay;

    Employee()
    {

    }

    Employee(String n, double s, int year, int month, int day)
    {
        this.name = n;
        this.salary = s;
        hireDay = LocalDate.of(year, month, day);
    }

    String getName()
    {
        return name;
    }

    double getSalary()
    {
        return salary;
    }

    LocalDate getHireDay()
    {
        return hireDay;
    }

    void raiseSalary(double byPercent)
    {
        double raise = salary * byPercent / 100;
        salary += raise;
    }

    /**
     * @Override
     */
    public String toString()
    {
        return getClass().getName() + "[name=" + name + ",salary=" + salary + ",hireDay=" + hireDay + "]";
    }
}
