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
        public $setting_value;
        
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
        }
        
        /**
         * تهيئة حقول النموذج في صفحة الإعدادات
         * تحدد الحقول التي ستظهر في قسم تكامل Woo Sham
         */
        public function init_form_fields() {
            $this->form_fields = array(
                'setting_value' => array(
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
                WC_Admin_Settings::add_error(esc_html__(' Looks Like you made   ','woo-sham'));
                return $this->currency_pairs;
            }
            
        }
    }
}