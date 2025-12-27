<?php

// add_action( 'wp_enqueue_scripts', 'storefront_child_enqueue_styles' );
// function storefront_child_enqueue_styles() {
//     $parenthandle = 'storefront-style'; // This is 'storefront-style' for the Storefront theme.
//     $theme = wp_get_theme();
//     wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css', 
//         array(),  // if the parent theme code has a dependency, copy it to here
//         $theme->parent()->get('Version')
//     );
//     wp_enqueue_style( 'storefront-child-style', get_stylesheet_uri(),
//         array( $parenthandle ),
//         $theme->get('Version') // this only works if you have Version in the style header
//     );
// }

// add_action('template_redirect', 'custom_cart_notice_for_blocks');

// function custom_cart_notice_for_blocks() {
//     $minimum = 25000;
//     if (WC()->cart->total < $minimum) {
//         if (is_cart()) {
//             wc_print_notice(
//                 sprintf(
//                     'Your current order total is %s — you must have an order with a minimum of %s to place your order.',
//                     wc_price(WC()->cart->total),
//                     wc_price($minimum)
//                 ),
//                 'error'
//             );
//         } else {
//             wc_add_notice(
//                 sprintf(
//                     'Your current order total is %s — you must have an order with a minimum of %s to place your order.',
//                     wc_price(WC()->cart->total),
//                     wc_price($minimum)
//                 ),
//                 'error'
//             );
//         }
//     }
// }

add_action( 'woocommerce_check_cart_items', 'custom_min_order_check' );

function custom_min_order_check() {
    $minimum = 500;

    if ( WC()->cart->get_total( 'edit' ) < $minimum ) {

        wc_add_notice( 
            sprintf( 
                'مجموع طلباتك هو%s — يجب ان يكون مجموع سعر طلباتك على اقل هو  %s ضع المزيد من المنتجات من فضلك', 
                wc_price( WC()->cart->get_total( 'edit' ) ), 
                wc_price( $minimum ) 
            ), 
            'error' 
        );
    }
}

add_filter('woocommerce_countries', 'wc_add_my_country');
function wc_add_my_country($countries) {
    $new_countries = array( 'SA' => __('Saudi Arabia','storefront-child') );
    return array_merge($countries, $new_countries);
}

add_filter('woocomerce_continents', 'wc_add_my_country_to_continent');
function wc_add_my_country_to_continent($continents) {
    $continents['AS']['countries'][] = __('SA','storefront-child');
    return $continents;
}

function online_summit_desc(){
    global $product;
    if ($product->get_type() == 'online_summit'){
        echo '<div class="summit_desc">
        <p> <strong> '. esc_html__('Event Start at:' , 'storefront-child') . ' </strong><i>'.esc_html__($product->get_meta('wcp_online_summit_start_date' , true)).'
        </i><strong> '.esc_html__($product->get_meta('wcp_online_summit_timezone' , true)).' </strong></p>
        <p>
            <strong> '. esc_html__('Event End at:' , 'storefront-child') . ' </strong><i>'.esc_html__($product->get_meta('wcp_online_summit_end_date' , true)).'</i>
            </i><strong> '.esc_html__($product->get_meta('wcp_online_summit_timezone' , true)).' </strong></p>    
        </p> 
        </div>
        ';
    }
}
add_action('woocommerce_single_product_summary', 'online_summit_desc' , 5);

function remove_storefront_sidebar(){
    if ( is_shop() || is_product()){
        remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
    }
}
add_action('get_header', 'remove_storefront_sidebar');

function storefront_footer_widgets(){
    return;
}

function storefront_credit(){
    ?>
    <div class="site-info-footer">
        <?php 
        echo esc_html__('جميع حقوق والنشر محفوظة عند ' . get_bloginfo('name'),'storefront-child'); 
        ?>
    </div>
    <?php
}

function storefront_remove_storefront_broadcrumbs(){
    if(is_shop()){
        remove_action( 'storefront_before_content', 'storefront_breadcrumb', 10 );
    }
}
add_action('storefront_before_header', 'storefront_remove_storefront_broadcrumbs');

function not_a_shop_page(){
     return boolval(!is_shop());
}
add_filter('storefront_show_page_title', 'not_a_shop_page');

function remove_sale_flashh(){
    return false;
} 
add_filter('woocommerce_sale_flash', 'remove_sale_flashh');

function sale_badge_before_the_product_image(){
    echo '<div class="shop-thumnail-wrap">';
    global $product;
    if ( $product->is_on_sale() ) {
        echo '<span class="onsale">' . esc_html__( 'Sale!', 'storefront-child' ) . '</span>';
    }
}
add_action('woocommerce_before_shop_loop_item_title', 'sale_badge_before_the_product_image' , 9);


function close_the_shop_thumnail_wrapper(){
    echo '<div class="overlay overlayFade"></div>';
    echo '</div>';
}
add_action('woocommerce_after_shop_loop_item_title', 'close_the_shop_thumnail_wrapper' , 11);


require  get_stylesheet_directory() . '/inc/wcp-online-summit.php';
require  get_stylesheet_directory() . '/inc/share-button.php';
require  get_stylesheet_directory() . '/inc/products-display.php';
require  get_stylesheet_directory() . '/inc/header-display.php';


