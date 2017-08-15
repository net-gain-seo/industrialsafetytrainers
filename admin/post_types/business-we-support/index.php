<?php

add_action( 'init', 'create_business_we_support_community_post_types' );

//CUSTOM POST TYPE
function create_business_we_support_community_post_types() {
    register_post_type( 'business-we-support',
        array(
            'labels' => array(
                'name' => 'Business We Support',
                'singular_name' => 'Business',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Business',
                'edit' => 'Edit',
                'edit_item' => 'Edit Business',
                'new_item' => 'New Business',
                'view' => 'View',
                'view_item' => 'View Business',
                'search_items' => 'Search Business',
                'not_found' => 'No Business found',
                'not_found_in_trash' => 'No Business found in Trash'
            ),
            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor','thumbnail'),
            'taxonomies' => array( '' ),
            'menu_icon' => '',
            'has_archive' => true,
            'rewrite' => array( 'slug' => 'business-we-support', 'with_front' => false ),
        )
    );
}