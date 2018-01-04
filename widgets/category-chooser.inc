<?php

function render_category_chooser ($id, $name, $current_value)
{
        echo "<select id=\"$id\" name=\"$name\">\n";
        foreach (get_categories() as $category) {
            printf("<option value=\"%d\" %s>%s</option>\n",
                   $category->cat_ID,
                   selected( $current_value, $category->cat_ID),
                   $category->cat_name);
        }
        echo "</select>\n";
}
