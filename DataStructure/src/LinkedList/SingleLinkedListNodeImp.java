package LinkedList;

public class SingleLinkedListNodeImp implements SingleLinkedListNode {

    private Object data;

    private int next;

    @Override
    public int getNext() {
        return this.next;
    }

    @Override
    public Object getData() {
        return data;
    }

    @Override
    public void setData(Object data) {
        this.data = data;
    }

    @Override
    public void setNext(int index) {
        this.next = index;
    }
}
