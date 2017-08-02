<?php 


// ENQUEUE STYLES AND SCRIPTS
function industrial_safety_trainers_scripts() {
    wp_enqueue_style( 'style-name', get_stylesheet_directory_uri().'/src/css/main.css',array('bootstrap') );
}
add_action( 'wp_enqueue_scripts', 'industrial_safety_trainers_scripts' );