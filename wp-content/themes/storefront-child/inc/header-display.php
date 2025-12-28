<?php

add_action('customize_register', 'storefront_child_customize_header_display' );

function storefront_child_customize_header_display($wp_customize){
    $wp_customize->add_section('header_display', array(
        'title' => __('Header Display Settings', 'storefront-child'),
        'priority' => 30,
        'panel' => 'storefront_child',
    ));
    $wp_customize->add_setting('header_display_theme',array('type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => 'theme1',
        'section' => 'header_display',
        'transport' => 'postMessage',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('header_display_theme', array(
        'type' => 'radio',
        'priority' => 10,
        'section' => 'header_display',
        'label' => __('Header Theme', 'storefront-child'),
        'description' => __('Change the header display style.', 'storefront-child'),
        'choices' => array(
            'theme1' => __('Theme 1', 'storefront-child'),
            'theme2' => __('Theme 2', 'storefront-child'),
            'theme3' => __('Theme 3', 'storefront-child'),
            'theme4' => __('Theme 4', 'storefront-child'),
        ),

    ));
}

if(get_theme_mod('header_display_theme') == 'theme1'){
    return;
}
elseif(get_theme_mod('header_display_theme') == 'theme2'){
    add_action('get_footer', function(){
    wp_enqueue_style('header-theme2' , get_stylesheet_directory_uri() . '/assets/css/header-display-theme2.css');  
    });
}
elseif(get_theme_mod('header_display_theme') == 'theme3'){
    add_action('get_footer', function(){
    wp_enqueue_style('header-theme3' , get_stylesheet_directory_uri() . '/assets/css/header-display-theme3.css');  
    });
}
elseif(get_theme_mod('header_display_theme') == 'theme4'){
    add_action('get_footer', function(){
    wp_enqueue_style('header-theme4' , get_stylesheet_directory_uri() . '/assets/css/header-display-theme4.css');  
    });
}
?>