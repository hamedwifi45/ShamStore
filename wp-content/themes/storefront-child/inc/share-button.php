<?php 
// add_action('init' , 'register_share_button_shortcode');
// function register_share_button_shortcode() {
//     add_shortcode('share_button', 'share_button_shortcode_handler');
// }
add_action('woocommerce_single_product_summary', 'share_button_shortcode_handler',50);
function share_button_shortcode_handler($atts) {
    $url = urlencode(get_permalink());
    $title = urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8'));

    ?>
    <div>
        <ul class="share_button">
            <?php if(get_theme_mod('storefront_facebook_icon', default_value: true)): ?>
            <li>
                <a class=" share_facebook" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $url; ?>" target="_blank" title="شارك المنشور">
                    <!-- Facebook SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M22.675 0h-21.35c-.733 0-1.325.592-1.325 1.325v21.351c0 .732.592 1.324 1.325 1.324h11.495v-9.294h-3.128v-3.622h3.128v-2.672c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.466.099 2.797.143v3.24l-1.918.001c-1.504 0-1.796.715-1.796 1.763v2.313h3.587l-.467 3.622h-3.12v9.293h6.116c.732 0 1.324-.592 1.324-1.324v-21.35c0-.733-.592-1.325-1.324-1.325z"/></svg>
                    <div><?php _e('facebook', 'storefront-child'); ?></div>
                </a>
            </li>
            <?php endif; ?>
        <?php if(get_theme_mod('storefront_twitter_icon', default_value: true)): ?>
            <!-- x -->
            <li>
                <a class=" share_twitter" href="https://twitter.com/intent/tweet?url=<?php echo $url; ?>&text=<?php echo $title; ?>" target="_blank" title="شارك المنشور">
                    <!-- Twitter SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-2.723 0-4.928 2.206-4.928 4.928 0 .39.045.765.127 1.124-4.094-.205-7.725-2.165-10.158-5.144-.424.729-.666 1.577-.666 2.476 0 1.708.87 3.215 2.188 4.099-.807-.026-1.566-.247-2.228-.616v.062c0 2.385 1.693 4.374 3.946 4.827-.413.112-.849.171-1.296.171-.317 0-.626-.03-.928-.086.627 1.956 2.444 3.379 4.6 3.419-1.68 1.318-3.809 2.105-6.102 2.105-.396 0-.787-.023-1.17-.067 2.179 1.397 4.768 2.213 7.557 2.213 9.054 0 14-7.496 14-13.986 0-.21 0-.423-.015-.633.961-.695 1.8-1.562 2.46-2.549z"/></svg>
                    <div><?php _e('twitter', 'storefront-child'); ?></div>
                </a>
            </li>
            <?php endif; ?>
            <?php if(get_theme_mod('storefront_linkedin_icon', default_value: true)): ?>

            <!-- LinkedIn -->
             <li>
                <a class=" share_linkedin" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo $url; ?>" target="_blank" title="شارك المنشور">
                    <!-- LinkedIn SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M22.23 0h-20.46c-.974 0-1.77.796-1.77 1.77v20.459c0 .974.796 1.771 1.77 1.771h20.459c.974 0 1.771-.797 1.771-1.771v-20.459c0-.974-.797-1.77-1.771-1.77zm-15.538 20.452h-3.692v-11.692h3.692v11.692zm-1.846-13.331c-1.184 0-2.142-.959-2.142-2.142s.958-2.142 2.142-2.142c1.183 0 2.142.959 2.142 2.142s-.959 2.142-2.142 2.142zm13.384 13.331h-3.692v-5.604c0-1.337-.026-3.058-1.864-3.058-1.865 0-2.151 1.456-2.151 2.961v5.701h-3.692v-11.692h3.546v1.598h.051c.494-.936 1.699-1.922 3.497-1.922 3.741 0 4.432 2.462 4.432 5.659v6.357z"/></svg>
                    <div><?php _e('linkedin', 'storefront-child'); ?></div>
                </a>
            </li>
            <?php endif; ?>
            <?php if(get_theme_mod('storefront_pinterest_icon', default_value: true)): ?>
             <!-- Pinterest -->
              <li>
                <a class=" share_pinterest" href="https://www.pinterest.com/pin/create/button/?url=<?php echo $url; ?>" target="_blank" title="شارك المنشور">
                    <!-- Pinterest SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pinterest" viewBox="0 0 16 16">
                    <path d="M8 0a8 8 0 0 0-2.915 15.452c-.07-.633-.134-1.606.027-2.297.146-.625.938-3.977.938-3.977s-.239-.479-.239-1.187c0-1.113.645-1.943 1.448-1.943.682 0 1.012.512 1.012 1.127 0 .686-.437 1.712-.663 2.663-.188.796.4 1.446 1.185 1.446 1.422 0 2.515-1.5 2.515-3.664 0-1.915-1.377-3.254-3.342-3.254-2.276 0-3.612 1.707-3.612 3.471 0 .688.265 1.425.595 1.826a.24.24 0 0 1 .056.23c-.061.252-.196.796-.222.907-.035.146-.116.177-.268.107-1-.465-1.624-1.926-1.624-3.1 0-2.523 1.834-4.84 5.286-4.84 2.775 0 4.932 1.977 4.932 4.62 0 2.757-1.739 4.976-4.151 4.976-.811 0-1.573-.421-1.834-.919l-.498 1.902c-.181.695-.669 1.566-.995 2.097A8 8 0 1 0 8 0"/>
                    </svg>
                    <div><?php _e('pinterest', 'storefront-child'); ?></div>
                </a>
            </li>
            <?php endif; ?>
            <?php if(get_theme_mod('storefront_whatsapp_icon', default_value: true)): ?>
             <!-- WhatsApp -->
              <li>
                <a class=" share_whatsapp" href="https://wa.me/?text=<?php echo $url; ?>" target="_blank" title="شارك المنشور">
                    <!-- WhatsApp SVG Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-whatsapp" viewBox="0 0 16 16">
                        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
                        </svg>
                    <div><?php _e('whatsapp', 'storefront-child'); ?></div>
                </a>
            </li>
            <?php endif; ?>
            <?php if(get_theme_mod('storefront_email_icon', default_value: true)): ?>
              <!-- Email -->
               <li>
                <a class=" share_email" href="mailto:?subject=<?php echo $title; ?>&body=<?php echo $url; ?>" target="_blank" title="شارك المنشور">
                    <!-- Email SVG Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M20 4h-16c-1.104 0-2 .896-2 2v12c0 1.104.896 2 2 2h16c1.104 0 2-.896 2-2v-12c0-1.104-.896-2-2-2zm0 4.236l-8 5.018-8-5.018v-2.236l8 5.018 8-5.018v2.236z"/></svg>
                    <div><?php _e('email', 'storefront-child'); ?></div>
                </a>
            </li>
            <?php endif; ?>
        </ul>

    </div>


<?php

}

add_action('wp_enqueue_scripts', 'enqueue_share_button_styles');
function enqueue_share_button_styles() {
    wp_enqueue_style('share-button-styles', get_stylesheet_directory_uri() . '/assets/share-button.css');
}



add_action('customize_register', 'storefront_child_share_button_settings');
function storefront_child_share_button_settings($wp_customize) {
    $wp_customize->add_panel('storefront_child', array(
        'title' => __('Woocommerce_advanced', 'storefront-child'),
        'priority' => 150,
    ));
    $wp_customize->add_section('storefront_social_share' , array(
        'title' => __('Social Share', 'storefront-child'),
        'panel' => 'storefront_child',
        
    ));
    $wp_customize->add_setting('storefront_facebook_icon', array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('storefront_facebook_icon', array(
        'label' => __('Enable/Disable Facebook Icon', 'storefront-child'),
        'section' => 'storefront_social_share',
        'settings' => 'storefront_facebook_icon',
        'type' => 'checkbox',
        'description' => __('Hide or Show Facebook share icon', 'storefront-child'),
    ));
    $wp_customize->add_setting('storefront_twitter_icon', array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('storefront_twitter_icon', array(
        'label' => __('Enable/Disable Twitter Icon', 'storefront-child'),
        'section' => 'storefront_social_share',
        'settings' => 'storefront_twitter_icon',
        'type' => 'checkbox',
        'description' => __('Hide or Show Twitter share icon', 'storefront-child'),
    ));
    $wp_customize->add_setting('storefront_linkedin_icon', array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('storefront_linkedin_icon', array(
        'label' => __('Enable/Disable LinkedIn Icon', 'storefront-child'),
        'section' => 'storefront_social_share',
        'settings' => 'storefront_linkedin_icon',
        'type' => 'checkbox',
        'description' => __('Hide or Show LinkedIn share icon', 'storefront-child'),
    ));
    $wp_customize->add_setting('storefront_pinterest_icon', array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('storefront_pinterest_icon', array(
        'label' => __('Enable/Disable Pinterest Icon', 'storefront-child'),
        'section' => 'storefront_social_share',
        'settings' => 'storefront_pinterest_icon',
        'type' => 'checkbox',
        'description' => __('Hide or Show Pinterest share icon', 'storefront-child'),
    ));
    $wp_customize->add_setting('storefront_whatsapp_icon', array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('storefront_whatsapp_icon', array(
        'label' => __('Enable/Disable WhatsApp Icon', 'storefront-child'),
        'section' => 'storefront_social_share',
        'settings' => 'storefront_whatsapp_icon',
        'type' => 'checkbox',
        'description' => __('Hide or Show WhatsApp share icon', 'storefront-child'),
    ));
    $wp_customize->add_setting('storefront_email_icon', array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'theme_supports' => '',
        'default' => true,
        'transport' => 'refresh',
        'sanitize_callback' => '',
        'sanitize_js_callback' => '',
    ));
    $wp_customize->add_control('storefront_email_icon', array(
        'label' => __('Enable/Disable Email Icon', 'storefront-child'),
        'section' => 'storefront_social_share',
        'settings' => 'storefront_email_icon',
        'type' => 'checkbox',
        'description' => __('Hide or Show Email share icon', 'storefront-child'),
    ));
}



?>