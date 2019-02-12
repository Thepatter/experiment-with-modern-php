package runtimeAnnotations;

import java.lang.annotation.*;
/**
 * @author zyw
 */
@Target(ElementType.METHOD)
@Retention(RetentionPolicy.RUNTIME)
public @interface ActionListenerFor {
    String source();
}
