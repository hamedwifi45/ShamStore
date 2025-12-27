<?php
// Add Product Type
function register_wcp_online_summit_product_type() {
    if(class_exists('WC_Product')){
        class WC_Online_Summit_Product extends WC_Product {
            public function __construct($product)
            {
                parent::__construct($product);
            }
            public function get_type() {
                return 'online_summit';
            }
            public function is_virtual()
            {
                return true;
            }
            public function add_to_cart_text() {
                return __('ضيف على سلة', 'storefront-child');
            }
        }
    }
}
add_action('init', 'register_wcp_online_summit_product_type');

// إضافة نوع المنتج للقائمة
function wcp_online_summit_product_class( $php_classname , $product_type ) {
    if ($product_type == 'online_summit') {
        $php_classname = 'WC_Online_Summit_Product';
    }
    return $php_classname;
}
add_filter( 'woocommerce_product_class', 'wcp_online_summit_product_class', 10, 2 );

function add_online_summit_product( $product_types ){
    $product_types['online_summit'] = __( 'نوع جديد للمنتج اسمو سوميت', 'storefront-child' );
    return $product_types;
}
add_filter( 'product_type_selector', 'add_online_summit_product' );

// التحكم في التبويبات
function wcp_online_summit_hide_data_tabs($tabs) {
    // إظهار التبويبات الأساسية والمخزون
    $tabs['general']['class'][] = 'show_if_online_summit';
    $tabs['inventory']['class'][] = 'show_if_online_summit';
    
    // إخفاء التبويبات الأخرى
    $tabs['attribute']['class'][] = 'hide_if_online_summit';
    $tabs['shipping']['class'][] = 'hide_if_online_summit';
    $tabs['linked_product']['class'][] = 'hide_if_online_summit';
    $tabs['advanced']['class'][] = 'hide_if_online_summit';
    
    return $tabs;
}
// تصحيح هنا: تأكد من أن الاسم يتطابق مع تعريف الدالة
add_filter('woocommerce_product_data_tabs', 'wcp_online_summit_hide_data_tabs');

// JavaScript للتحكم في العرض
function wcp_online_summit_custom_js() {
    if ('product' != get_post_type()) {
        return;
    }
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#product-type').on('change', function() {
                if($('#product-type').val() == 'online_summit') {
                    // إظهار التبويبات المطلوبة
                    $('.options_group.pricing').addClass('show_if_online_summit').show();
                    $('.general_tab').show();
                    
                    // تعيين ضريبة لا شيء
                    $('select[name="_tax_status"]').val('none');
                    $('._tax_status_field').parent().addClass('show_if_online_summit').show();
                    
                    // التحكم في المخزون
                    $('#inventory_product_data ._manage_stock_field').addClass('show_if_online_summit').show();
                    $('#inventory_product_data ._sold_individually_field').parent().addClass('show_if_online_summit').show();
                    $('#inventory_product_data ._sold_individually_field').addClass('show_if_online_summit').show();
                }
            });
            $('#product-type').trigger('change');
        });
    </script>
    <?php
}
add_action('admin_footer', 'wcp_online_summit_custom_js');

function wcp_online_summit_product_tabs($tabs) {

    $tabs['wcp_online_summit'] = array(
        'label'    => __('معلومات السوميت','storefront-child'),
        'target' => 'wcp_online_summit_options',
        'class' => array('show_if_online_summit'),
    );
    return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'wcp_online_summit_product_tabs');



function wcp_online_summit_tab_content() {
    global $product_object; ?>
    <div id='wcp_online_summit_options' class="panel woocommerce_options_panel">
        <?php
        // اسم السوميت 
        woocommerce_wp_text_input( 
            array( 
                'id'          => 'wcp_online_summit_name',
                'label'       => __('اسم السوميت','storefront-child'),
                'desc_tip'    => 'true',
                'type'        => 'text',
                'description' => __('ادخل اسم السوميت هنا','storefront-child'),
                'value'       => $product_object->get_meta( 'wcp_online_summit_name', true )
                
                ) 
        );
        // بداية السوميت
        woocommerce_wp_text_input( 
            array( 
                'id'          => 'wcp_online_summit_start_date',
                'label'       => __('تاريخ بداية السوميت','storefront-child'),
                'desc_tip'    => 'true',
                'description' => __('ادخل تاريخ بداية السوميت هنا','storefront-child'),
                'value'       => get_post_meta( $product_object->get_id(), 'wcp_online_summit_start_date', true ),
                'type'        => 'text',
                'style'    => 'direction:ltr;',
            ) 
        );
        // نهاية السوميت
        woocommerce_wp_text_input( 
            array( 
                'id'          => 'wcp_online_summit_end_date',
                'label'       => __('تاريخ نهاية السوميت','storefront-child'),
                'desc_tip'    => 'true',
                'description' => __('ادخل تاريخ نهاية السوميت هنا','storefront-child'),
                'value'       => $product_object->get_meta(  'wcp_online_summit_end_date', true ),
                'type'        => 'text',
                'style'    => 'direction:ltr;',
            ) 
        );
        // المنطقة الزمنية
        woocommerce_wp_text_input( 
            array( 
                'id'          => 'wcp_online_summit_timezone',
                'label'       => __('المنطقة الزمنية للسوميت','storefront-child'),
                'desc_tip'    => 'true',
                'style'    => 'direction:rtl;',
                'description' => __('ادخل المنطقة الزمنية للسوميت هنا','storefront-child'),
                'value'       => $product_object->get_meta('wcp_online_summit_timezone', true ),
                'type'        => 'text',
            ) 
        );
        echo '<div class="summit_dates_fields">
        <p class="form-field summit_date_from_field" style="display:block;">
            <label for="_summit_date_from">' . esc_html__('Summit Date Range', 'storefront-child') . '</label>
            ' . wc_help_tip(__('Enter in which dates the summit will start and finish', 'storefront-child')) . '
            <input type="text" class="short" id="_summit_date_from" name="_summit_date_from" value="' . esc_attr($product_object->get_meta('_summit_date_from', true)) . '" placeholder="' . esc_attr_x('From…', 'placeholder', 'storefront-child') . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr(apply_filters('woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) . '" />
        </p>
        <p class="form-field summit_date_to_field" style="display:block;">
            <label for="_summit_date_to">' . esc_html__('To', 'storefront-child') . '</label>
            <input type="text" class="short" id="_summit_date_to" name="_summit_date_to" value="' . esc_attr($product_object->get_meta('_summit_date_to', true)) . '" placeholder="' . esc_attr_x('To…', 'placeholder', 'storefront-child') . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr(apply_filters('woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])')) . '" />
        </p>
    </div>';

        ?>
        
    </div>
<?php
}
add_action('woocommerce_product_data_panels', 'wcp_online_summit_tab_content');

// 
// حفظ حقول السوميت
// 

function store_summit_product_fields($product_id){
    if(current_user_can('manage_options')){
     $summit_name = isset($_POST['wcp_online_summit_name']) ? sanitize_text_field($_POST['wcp_online_summit_name']) : '';
     Update_post_meta($product_id, 'wcp_online_summit_name', $summit_name);

     $summit_from = isset($_POST['wcp_online_summit_start_date']) ? $_POST['wcp_online_summit_start_date'] : '';
     update_post_meta($product_id,'wcp_online_summit_start_date' , $summit_from);

     $summit_to = isset($_POST['wcp_online_summit_end_date']) ? $_POST['wcp_online_summit_end_date'] : '';
     update_post_meta($product_id,'wcp_online_summit_end_date' , $summit_to);

    $summit_timezone = isset($_POST['wcp_online_summit_timezone']) ? sanitize_text_field($_POST['wcp_online_summit_timezone']) : '';
     update_post_meta($product_id, 'wcp_online_summit_timezone', $summit_timezone);
    
    $summit_date_from = isset($_POST['_summit_date_from']) ? sanitize_text_field($_POST['_summit_date_from']) : '';
    update_post_meta($product_id, '_summit_date_from', $summit_date_from);

    $summit_date_to = isset($_POST['_summit_date_to']) ? sanitize_text_field($_POST['_summit_date_to']) : '';
    update_post_meta($product_id, '_summit_date_to', $summit_date_to);

        wp_set_object_terms( $product_id, array('Events'),'product_cat' );
        wp_set_object_terms( $product_id, array('online_summit' , 'virtual'),'product_cat' );
    }
}
add_action('woocommerce_process_product_meta_online_summit', 'store_summit_product_fields'); 


function wcp_online_summit_type_in_body_class($classes){
    if (is_product()){
        $product = wc_get_product();
        if($product->get_type() == 'online_summit'){
            $classes[] = 'online-summit-product-page';
        }
    }
    return $classes;
}
add_filter('body_class', 'wcp_online_summit_type_in_body_class');

add_action('woocommerce_online_summit_add_to_cart' , function(){
    do_action('woocommerce_simple_add_to_cart');

} );





?>
