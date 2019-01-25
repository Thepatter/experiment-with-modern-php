package locale;

import javax.swing.*;
import java.util.Map;
import java.util.TreeMap;

class EnumCombo<T> extends JComboBox<String> {
    private Map<String, T> table = new TreeMap<>();

    EnumCombo(Class<?> cl, String... labels) {
        for (String label : labels) {
            String name = label.toUpperCase().replace(' ', '_');
            try {
                java.lang.reflect.Field f = getClass().getField(name);
                @SuppressWarnings("unchecked") T value = (T) f.get(cl);
                table.put(label, value);
            } catch (Exception e) {
                label = "(" + label + ")";
                table.put(label, null);
            }
            addItem(label);
        }
        setSelectedItem(labels[0]);
    }

    T getValue() {
        return table.get(getSelectedItem());
    }
}
