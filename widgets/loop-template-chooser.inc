<?php

namespace EPFL\STI\Theme\Widgets;

function list_loop_templates ()
{
    $loop_templates = array();
    foreach (scandir(dirname(__DIR__) . "/loop-templates") as $filename) {
        $matched = array();
        if (preg_match('/^content-(.*)\.php$/', $filename, $matched)) {
            $loop_templates[$matched[1]] = "loop-templates/$filename";
        }
    }
    return $loop_templates;
}

function render_loop_template_chooser ($id, $name, $current_value)
{
        echo "<select id=\"$id\" name=\"$name\">\n";
        foreach (list_loop_templates() as $key => $fullpath) {
            printf("<option value=\"%s\" %s><pre>%s</pre></option>\n",
                   $key,
                   selected($current_value, $key),
                   $fullpath);
        }
        echo "</select>\n";
}
