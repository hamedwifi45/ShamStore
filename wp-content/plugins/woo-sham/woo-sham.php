<?php
/**
 * Plugin Name: Woo Sham
 * Description: A plugin to add custom functionality for WooCommerce Sham Store
 * Version: 1.0
 * Author: ABD ALHAMID
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: woo-sham
 * Domain Path: /languages
 */

// منع الوصول المباشر للملف
if(!defined('ABSPATH')) {
    exit;
}

// تحقق من عدم وجود الكلاس مسبقاً لتجنب التكرار
if (!class_exists('Woo_Sham')) {
    
    /**
     * الكلاس الرئيسي للإضافة
     * يدير جميع وظائف إضافة Woo Sham
     */
    class Woo_Sham {
        
        /**
         * نسخة واحدة من الكلاس (نمط Singleton)
         * @var Woo_Sham|null
         */
        private static $instance = null;
        
        /**
         * إصدار الإضافة
         * @var string
         */
        const PLUGIN_VERSION = '1.0';
        
        /**
         * الحد الأدنى لإصدار PHP المطلوب
         * @var string
         */
        const MINIMUM_PHP_VERSION = '8.0.0';
        
        /**
         * الحد الأدنى لإصدار WordPress المطلوب
         * @var string
         */
        const MINIMUM_WP_VERSION = '5.0.0';
        
        /**
         * الحد الأدنى لإصدار WooCommerce المطلوب
         * @var string
         */
        const MINIMUM_WC_VERSION = '4.0.0';
        
        /**
         * ترجع نسخة واحدة من الكلاس (نمط Singleton)
         * تضمن وجود نسخة واحدة فقط من الكلاس في الذاكرة
         * @return Woo_Sham
         */
        public static function instance() {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        
        /**
         * المُنشئ - يتم استدعاؤه عند إنشاء الكائن
         * يسجل جميع الهوكات والإجراءات المطلوبة
         */
        protected function __construct() {
            // هوك التحقق عند تفعيل الإضافة
            register_activation_hook(__FILE__, array($this, 'activation_check'));
            
            // تهيئة الإضافة في لوحة التحكم
            add_action('admin_init', array($this, 'init_plugin'));
            
            // تسجيل حالة الطلب الجديدة
            add_action('init', array($this, 'register_building_order_status'));
            
            // إضافة حالة الطلب الجديدة إلى WooCommerce
            add_filter('wc_order_statuses', array($this, 'add_building_to_order_statuses'));
            
            // تهيئة التكامل مع WooCommerce
            add_action('plugins_loaded', array($this, 'init_integration'));
        }
        
        /**
         * التحقق من متطلبات النظام عند تفعيل الإضافة
         * تتحقق من إصدار PHP فقط
         */
        public function activation_check() {
            if(!version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=')) {
                $this->deactivate_plugin();
                wp_die('This plugin requires PHP version ' . self::MINIMUM_PHP_VERSION . ' or higher. Please update your PHP version.');
            }
        }
        
        /**
         * تعطيل الإضافة بطريقة آمنة
         * تزيل الإضافة من قائمة الإضافات النشطة
         */
        protected function deactivate_plugin() {
            deactivate_plugins(plugin_basename(__FILE__));
            if(isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
        
        /**
         * تهيئة الإضافة بعد التحقق من التوافق
         * تنفذ بعد تحميل لوحة التحكم
         */
        public function init_plugin() {
            if(!$this->is_compatible()) {  
                return;
            }
            
            // إضافة حالة الطلب الجديدة (تم تسجيلها مسبقاً في init)
            add_filter('wc_order_statuses', array($this, 'add_building_to_order_statuses'));
        }
        
        /**
         * تهيئة تكامل WooCommerce
         * تحميل فئة التكامل وإضافتها إلى إعدادات WooCommerce
         */
        public function init_integration() {
            // تحميل فئة التكامل إذا لم تكن موجودة
            if(!class_exists('Woo_Sham_Integration')) {
                include_once 'class-woo-sham-integration.php';
            }
            
            // إضافة التكامل إلى قائمة تكاملات WooCommerce
            add_filter('woocommerce_integrations', array($this, 'add_integration'));
            
            // إضافة رابط الإعدادات إلى صفحة الإضافات
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'woo_sham_action_links'));
        }
        
        /**
         * تسجيل حالة طلب جديدة في النظام
         * تنشئ حالة طلب جديدة باسم "Building"
         */
        public function register_building_order_status() {
            register_post_status('wc-building', array(
                'label'                     => _x('Building', 'Woo Order status', 'woo-sham'),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop('Building <span class="count">(%s)</span>', 'Building <span class="count">(%s)</span>', 'woo-sham')
            ));
        }
        
        /**
         * إضافة حالة الطلب الجديدة إلى قائمة حالات WooCommerce
         * تضاف حالة "Building" بعد حالة "Processing"
         * @param array $order_statuses قائمة حالات الطلب الحالية
         * @return array قائمة حالات الطلب المعدلة
         */
        public function add_building_to_order_statuses($order_statuses) {
            $new_order_statuses = array();
            
            // إضافة حالة الطلب الجديدة بعد حالة 'processing'
            foreach ($order_statuses as $key => $status) {
                $new_order_statuses[$key] = $status;
                if ('wc-processing' === $key) {
                    $new_order_statuses['wc-building'] = esc_html__('Building', 'woo-sham');
                }
            }
            
            return $new_order_statuses;
        }
        
        /**
         * إضافة تكامل Woo Sham إلى قائمة تكاملات WooCommerce
         * @param array $integrations قائمة التكاملات الحالية
         * @return array قائمة التكاملات المعدلة
         */
        public function add_integration($integrations) {
            $integrations[] = 'Woo_Sham_Integration';
            return $integrations;
        }
        
        /**
         * إضافة رابط الإعدادات إلى صفحة الإضافات
         * يضيف رابط "Settings" بجوار زر التفعيل/تعطيل الإضافة
         * @param array $links روابط الإجراءات الحالية
         * @return array روابط الإجراءات المعدلة
         */
        function woo_sham_action_links($links) {
            $links[] = '<a href="' . admin_url('admin.php?page=wc-settings&tab=integration&section=woo-sham-integration') . '">'
                . esc_html__('Settings', 'woo-sham') . '</a>';
            return $links;
        }
        
        /**
         * التحقق من توافق الإضافة مع النظام
         * تتحقق من إصدارات WordPress وWooCommerce
         * @return bool true إذا كان متوافقاً، false إذا لم يكن
         */
        public function is_compatible() {
            // التحقق من إصدار WordPress
            if(version_compare(get_bloginfo('version'), self::MINIMUM_WP_VERSION, '<')) {
                add_action('admin_notices', array($this, 'admin_notice_minimum_wordpress_version'));
                $this->deactivate_plugin();
                return false;
            }
            
            // التحقق من إصدار WooCommerce
            if(!defined('WC_VERSION') || version_compare(WC_VERSION, self::MINIMUM_WC_VERSION, '<')) {
                add_action('admin_notices', array($this, 'admin_notice_missing_woocommerce'));
                $this->deactivate_plugin();
                return false;
            }
            
            // التحقق من وجود WooCommerce
            if(!class_exists('WooCommerce')) {
                add_action('admin_notices', array($this, 'admin_notice_missing_woocommerce'));
                $this->deactivate_plugin();
                return false;
            }
            
            return true;
        }
        
        /**
         * عرض رسالة تحذير إذا كان WooCommerce مفقوداً
         * تعرض خيارين: تثبيت أو تفعيل WooCommerce
         */
        public function admin_notice_missing_woocommerce() {
            $woocommerce = 'woocommerce/woocommerce.php';
            $pathpluginurl = WP_PLUGIN_DIR . '/' . $woocommerce;
            $isinstalled = file_exists($pathpluginurl);
            
            if($isinstalled && !is_plugin_active($woocommerce)) {
                // WooCommerce مثبت لكن غير مفعل
                if(!current_user_can('activate_plugins')) {
                    return;
                }
                $activition_url = wp_nonce_url('plugins.php?action=activate&plugin=' . $woocommerce, 'plugin_status=all&paged=1&amp;s','activate-plugin_' . $woocommerce);
                $message = sprintf(__('%1$sWoo Sham%2$s requires %1$s"WooCommerce"%2$s plugin to be active. Please active WooCommerce to continue', 'woo-sham'), '<strong>', '</strong>');
                $btn_text = esc_html__('Activate WooCommerce', 'woo-sham');
            } else {
                // WooCommerce غير مثبت
                if(!current_user_can('install_plugins')) {
                    return;
                }
                $activition_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
                $message = sprintf(__('%1$sWoo Sham%2$s requires %1$s"WooCommerce"%2$s plugin to be installed and active. Please active WooCommerce to continue', 'woo-sham'), '<strong>', '</strong>');
                $btn_text = esc_html__('Install WooCommerce', 'woo-sham');
            }
            
            $btn = '<p> <a href="'.$activition_url.'" class="button-primary">'. $btn_text .'</a> </p>';
            printf('<div class="notice notice-warning is-dismissible" ><p>%1$s</p>%2$s</div>', $message, $btn);
        }
        
        /**
         * عرض رسالة تحذير إذا كان إصدار WordPress قديماً
         */
        public function admin_notice_minimum_wordpress_version() {
            $message = sprintf(
                esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'woo-sham'),
                '<strong>'. esc_html__('Woo Sham', 'woo-sham') .'</strong>',
                '<strong>'. esc_html__('WordPress', 'woo-sham') .'</strong>',
                self::MINIMUM_WP_VERSION
            );
            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
        }
    }
    
    // بدء تشغيل الإضافة
    Woo_Sham::instance();
}