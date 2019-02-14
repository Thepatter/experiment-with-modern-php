package permissions;

import java.security.Permission;
import java.util.Arrays;
import java.util.HashSet;
import java.util.Objects;
import java.util.Set;

/**
 * @author zyw
 */
public class WordCheckPermission extends Permission {
    private String action;

    public WordCheckPermission(String target, String anAction) {
        super(target);
        action = anAction;
    }
    @Override
    public String getActions() {
        return action;
    }

    @Override
    public boolean equals(Object obj) {
        if (obj == null) {
            return false;
        }
        if (!getClass().equals(obj.getClass())) {
            return false;
        }
        WordCheckPermission b = (WordCheckPermission) obj;
        if (!Objects.equals(action, b.action)) {
            return false;
        }
        if ("insert".equals(action)) {
            return Objects.equals(getName(), b.getName());
        } else if ("avoid".equals(action)) {
            return badWordSet().equals(b.badWordSet());
        } else {
            return false;
        }
    }
    @Override
    public int hashCode() {
        return Objects.hash(getName(), action);
    }
    @Override
    public boolean implies(Permission other) {
        if (!(other instanceof WordCheckPermission)) {
            return false;
        }
        WordCheckPermission b = (WordCheckPermission) other;
        if (action.equals("insert")) {
            return b.action.equals("insert") && getName().indexOf(b.getName()) >= 0;
        } else if (action.equals("avoid")) {
            if (b.action.equals("avoid")) {
                return b.badWordSet().containsAll(badWordSet());
            } else if (b.action.equals("insert")) {
                for (String badWord : badWordSet()) {
                    if (b.getName().indexOf(badWord) >= 0) {
                        return false;
                    }
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public Set<String> badWordSet() {
        return new HashSet<>(Arrays.asList(getName().split(",")));
    }
}
