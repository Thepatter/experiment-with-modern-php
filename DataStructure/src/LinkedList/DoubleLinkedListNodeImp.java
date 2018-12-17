package LinkedList;

public class DoubleLinkedListNodeImp implements DoubleLinkedListNode {

    private int next;

    private int prev;

    private Object data;

    @Override
    public void setNext(int next) {
        this.next = next;
    }

    @Override
    public void setData(Object data) {
        this.data = data;
    }

    @Override
    public void setPrev(int prev) {
        this.prev = prev;
    }

    @Override
    public Object getData() {
        return data;
    }

    @Override
    public int getNext() {
        return next;
    }

    @Override
    public int getPrev() {
        return prev;
    }


}
