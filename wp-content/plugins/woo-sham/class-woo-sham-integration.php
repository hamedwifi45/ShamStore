<?php 
/**
 * فئة تكامل Woo Sham مع WooCommerce
 * تمدد فئة WC_Integration لتوفير إعدادات مخصصة
 */
if(!class_exists('Woo_Sham_Integration')) {
    
    class Woo_Sham_Integration extends WC_Integration {
        
        /**
         * قيمة الإعدادات المخزنة
         * @var string
         */
        public $currency_pairs;
        
        /**
         * المُنشئ - يهيئ فئة التكامل مع WooCommerce
         */
        public function __construct() {
            global $woocommerce;
            
            // تعريف معرّف التكامل وعنوانه ووصفه
            $this->id                 = 'woo-sham-integration';
            $this->method_title       = __('Woo Sham Integration', 'woo-sham');
            $this->method_description = __('Woo Sham Integration is a first test', 'woo-sham');
            
            // تحميل الحقول والإعدادات
            $this->init_form_fields();
            $this->init_settings();
            
            // تعيين القيمة المدخلة من قبل المستخدم
            $this->currency_pairs = $this->get_option('currency_pairs');
            



            // حفظ الإعدادات عند التحديث
            add_action('woocommerce_update_options_integration_' . $this->id, array($this, 'process_admin_options'));
            add_shortcode('currency_selector' , array($this, 'display_select_currency'));
        }
        
        /**
         * تهيئة حقول النموذج في صفحة الإعدادات
         * تحدد الحقول التي ستظهر في قسم تكامل Woo Sham
         */
        public function init_form_fields() {
            $this->form_fields = array(
                'currency_pairs' => array(
                    'title'       => __('Currency Pairs', 'woo-sham'),
                    'type'        => 'textarea',
                    'description' => __('Enter Currency Pairs \n Ex: EUR:1.9 \n SYR:99', 'woo-sham'),
                    'desc_tip'    => true,
                    'default'     => '',
                    'css'         => 'width:full;'
                )
            );
        }
        public function Validate_currency_pairs_field($key , $value){
            try{
                if(!empty($value)){
                    $lines = explode("\n" , $value);
                    $pairs = array();
                    foreach($lines as $line){
                        $pair = explode(":", $line );
                        if(empty($pair[1])){
                            throw new Exception;
                        }
                        $pairs[$pair[0]] = $pair[1];
                    }
                    update_option('woo_currency_pairs' , $pairs);
                    return $value;
                }
            }catch(\Throwable $th){
                WC_Admin_Settings::add_error(esc_html__('An error occurred. Please make sure you have placed : between the currency name and its value.','woo-sham'));
                return $this->currency_pairs;
            }
        }
        public function display_select_currency(){
            $cur_selected = 'not set';
            if(isset($_COOKIE['currency'])){
                $cur_selected = $_COOKIE['currency'];
            }
            $pairs = get_option('woo_currency_pairs');
            echo '<form action="" method="post" id="currency_selector"><label for="select_currency">' . esc_html__('Currency : ' , 'woo-sham') . '</label> 
             <select name="currency_selector" id="select_currency">
             <option value="' . get_woocommerce_currency() . '">' . get_woocommerce_currency() . '</option>';

             foreach ($pairs as $key => $value){
                $selected = ($cur_selected == $key ) ? 'selected' : '';
                $option = '<option'. $selected .' value="'.$key.'">'.$key.'</option>';
                echo $option;
            }
            echo '</select><input type="submit" value="'.esc_html__('set','woo-sham').'"></form>';
        }
    }
}