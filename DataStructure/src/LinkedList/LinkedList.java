package LinkedList;

public interface LinkedList extends Iterable {

    static final int  DilatationMultiplier = 2;

    public LinkedListNode[] toArray();

    public int getLinkedListLength();

    public boolean delByIndex(int $index);
}
