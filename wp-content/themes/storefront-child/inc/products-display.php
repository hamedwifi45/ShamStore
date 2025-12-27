<?php
add_action('customize_register', 'storefront_child_display_products' );

function storefront_child_display_products($wp_customize){
    $wp_customize->add_section('storefront_products_display_theme', array(
        'title' => __('Products Display Settings', 'storefront-child'),
        'priority' => 30,
        'panel' => 'storefront_child',
    ));
    $wp_customize->add_setting('storefront_display_theme',
    array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => 'theme2',
        'transport' => 'refresh',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('storefront_display_theme', array(
        'label' => __('Product Theme', 'storefront-child'),
        'section' => 'storefront_products_display_theme',
        'settings' => 'storefront_display_theme',
        'description' => __('Change the products display in the product archive page .', 'storefront-child'),
        'type' => 'radio',
        'choices' => array(
            'theme1' => __('Top', 'storefront-child'),
            'theme2' => __('Bottom', 'storefront-child'),
            'theme3' => __('Above', 'storefront-child'),
        ),
        'priority' => 10,
    ));
}


if(get_theme_mod('storefront_display_theme') == 'theme1'){
    remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart'); 
    remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price');
    remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title');

    add_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_title', 20);
    add_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_price', 30);
    add_action('woocommerce_before_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 40);
}















?>