<?php

/**
 * Customization of the admin area
 */

namespace EPFL\STI\WPAdmin;

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Access denied.' );
}

use function \get_page_templates;
use function \get_page_template_slug;

require_once(__DIR__ . "/i18n.php");
use function \EPFL\STI\Theme\___;

// Based on https://code.tutsplus.com/articles/quick-tip-make-your-custom-column-sortable--wp-25095
class SortableColumn
{
    function __construct ($column_slug, $options)
    {
        $this->column_slug = $column_slug;
        $this->post_type           = $options["post_type"];
        $this->taxonomy            = ($options["taxonomy"] ?
                                      $options["taxonomy"] :
                                      ($this->post_type . "s"));
        $this->title               = $options["title"];
        $this->column_content_func = $options["column_content"];
    }

    function hook ()
    {
        $slug      = $this->column_slug;
        $post_type = $this->post_type;
        add_filter("manage_edit-${post_type}_columns",
                   array($this, "manage_columns"));
        add_filter("manage_edit-${post_type}_sortable_columns",
                   array($this, "manage_sortable_columns"));
        $taxonomy = $this->taxonomy;
        add_action("manage_${taxonomy}_custom_column",
                   array($this, "render_column_content"), 10, 2 );

        add_action('pre_get_posts', array($this, "maybe_orderby"));
    }

    function manage_columns ($columns)
    {
        $columns[$this->column_slug] = $this->title;
        return $columns;
    }

    function manage_sortable_columns( $columns )
    {
        $columns[$this->column_slug] = $this->title;
        return $columns;
    }

    function render_column_content ($column_name, $post_id)
    {
        if ( $this->column_slug != $column_name ) return;

        echo $this->column_content_func->call($this, $post_id);
    }

    function maybe_orderby( $query )
    {
        if(! is_admin()) return;
        if ($query->get( 'orderby') !== $this->column_slug) return;

        $query->set('meta_key', $this->column_slug);
        $query->set('orderby', 'meta_value');
    }
}

(new SortableColumn("page-template", array(
    "post_type"      => "page",
    "title"          => ___("Page Template"),
    "column_content" => function($post_id) {
        $page_template_slug = get_page_template_slug($post_id);
        $templates_by_filename = array_flip(get_page_templates());
        return $templates_by_filename[$page_template_slug];
    }
)))->hook();
