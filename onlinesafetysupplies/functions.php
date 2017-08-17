<?php 


// ENQUEUE STYLES AND SCRIPTS
function construction_safety_trainers_scripts() {
    wp_enqueue_style( 'construction_safety_trainers_style', get_stylesheet_directory_uri().'/src/css/main.css',array('bootstrap','industrial_safety_trainers_style') );
}
add_action( 'wp_enqueue_scripts', 'construction_safety_trainers_scripts' );