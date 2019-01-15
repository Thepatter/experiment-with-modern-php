package iostream;

/**
 * @author zyw
 */
class Manager extends Employee {
    private Employee secretary;

    Manager(String n, double s, int year, int month, int day)
    {
        super(n, s, year, month, day);
        secretary = null;
    }

    void setSecretary(Employee e)
    {
        secretary = e;
    }

    @Override
    public String toString() {
        return super.toString() + "[secretary=" + secretary + "]";
    }
}
