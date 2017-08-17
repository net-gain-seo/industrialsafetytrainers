<?php

add_action( 'init', 'create_custom_in_the_community_post_types' );

//CUSTOM POST TYPE
function create_custom_in_the_community_post_types() {
    register_post_type( 'in-the-community',
        array(
            'labels' => array(
                'name' => 'In the Community',
                'singular_name' => 'Community Item',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Community Item',
                'edit' => 'Edit',
                'edit_item' => 'Edit Community Item',
                'new_item' => 'New Community Item',
                'view' => 'View',
                'view_item' => 'View Community Item',
                'search_items' => 'Search Community Items',
                'not_found' => 'No Community Items found',
                'not_found_in_trash' => 'No Community Items found in Trash'
            ),
            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor','thumbnail'),
            'taxonomies' => array( '' ),
            'menu_icon' => '',
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'in-the-community', 'with_front' => false ),
        )
    );
}