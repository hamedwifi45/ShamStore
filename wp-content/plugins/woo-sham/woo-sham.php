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
if(!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if (!class_exists('Woo_Sham')) {
    class Woo_Sham {

        private static $instance = null;
        const PLUGIN_Version = '1.0';
        const MINIMUM_PHP_VERSION = '8.0.0';

        const MINIMUM_WP_VERSION = '5.0.0';
        const MINIMUM_WC_VERSION = '4.0.0';
        
        public static function instance() {
            if (self::$instance == null) {
                self::$instance = new self();
            }
            return self::$instance;
        }
        protected function __construct() {
            register_activation_hook(__FILE__, array($this, 'activation_check'));
            add_action('admin_init' , array($this, 'init_plugin'));
            
            // إضافة الهوكات الجديدة
            add_action('init', array($this, 'register_building_order_status'));
            add_filter('wc_order_statuses', array($this, 'add_building_to_order_statuses'));
        }
        public function activation_check() {
            if(!version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '>=')) {
                $this->deactivate_plugin();
                wp_die('This plugin requires PHP version ' . self::MINIMUM_PHP_VERSION . ' or higher. Please update your PHP version.');
            }

        }
        protected function deactivate_plugin() {
            deactivate_plugins(plugin_basename(__FILE__));
            if(isset($_GET['activate'])) {
                unset($_GET['activate']);
            }
        }
        public function init_plugin() {
            if(!$this->is_compatible()) {  
                return;
            }
        }
        public function register_building_order_status(){
            register_post_status( 'wc-building', array(
                'label'                     => _x( 'Building', 'Woo Order status', 'woo-sham' ),
                'public'                    => false,
                'exclude_from_search'       => false,
                'show_in_admin_all_list'    => true,
                'show_in_admin_status_list' => true,
                'label_count'               => _n_noop( 'Building <span class="count">(%s)</span>', 'Building <span class="count">(%s)</span>', 'woo-sham' )
            ) );
        }
        public function add_building_to_order_statuses( $order_statuses ) {
            $new_order_statuses = array();
        
            // Add new order status after 'processing'
            foreach ( $order_statuses as $key => $status ) {
                $new_order_statuses[ $key ] = $status;
                if ( 'wc-processing' === $key ) {
                    $new_order_statuses['wc-building'] = esc_html__( 'Building', 'woo-sham' );
                }
            }
        
            return $new_order_statuses;
        }
        public function is_compatible() {
        if(version_compare(get_bloginfo('version'), self::MINIMUM_WP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'admin_notice_minimum_wordpress_version'));
            $this->deactivate_plugin();
            return false;
        }
    
        if(!defined('WC_VERSION') || version_compare(WC_VERSION, self::MINIMUM_WC_VERSION, '<')) {
            add_action('admin_notices', array($this, 'admin_notice_missing_woocommerce'));
            $this->deactivate_plugin();
            return false;
        }
    
        if(!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'admin_notice_missing_woocommerce'));
            $this->deactivate_plugin();
            return false;
        }
    
        return true;
        }
        public function admin_notice_missing_woocommerce() {
            $woocommerce = 'woocommerce/woocommerce.php';
            $pathpluginurl = WP_PLUGIN_DIR . '/' . $woocommerce;
            $isinstalled = file_exists($pathpluginurl);
            if($isinstalled && !is_plugin_active($woocommerce)) {
                if(!current_user_can('activate_plugins')) {
                    return;
                }
                $activition_url = wp_nonce_url('plugins.php?action=activate&plugin=' . $woocommerce, 'plugin_status=all&paged=1&amp;s','activate-plugin_' . $woocommerce);
                $message = sprintf(__('%1$sWoo Sham%2$s requires %1$s"WooCommerce"%2$s plugin to be active. Please active WooCommerce to continue' , 'woo-sham'), '<strong>', '</strong>');
                $btn_text = esc_html__('Activate WooCommerce', 'woo-sham'); }
                else {
                    if(!current_user_can('activate_plugins')) {
                        return;
                    }
                    $activition_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=woocommerce'), 'install-plugin_woocommerce');
                    $message = sprintf(__('%1$sWoo Sham%2$s requires %1$s"WooCommerce"%2$s plugin to be installed and active. Please active WooCommerce to continue' , 'woo-sham'), '<strong>', '</strong>');
                    $btn_text = esc_html__('Install WooCommerce', 'woo-sham');
                 }
                 $btn = '<p> <a href="'.$activition_url.'" class="button-primary">'. $btn_text .'</a> </p>';
                 printf('<div class="notice notice-warning is-dismissible" ><p>%1$s</p>%2$s</div>' , $message , $btn);
        } 
        public function admin_notice_minimum_wordpress_version() {
            $message = sprintf(
                esc_html__('"%1$s" requires "%2$s" version %3$s or greater.' , 'woo-sham'),
                '<strong>'. esc_html__('Woo Sham', 'woo-sham') .'</strong>',
                '<strong>'. esc_html__('WordPress', 'woo-sham') .'</strong>',
                self::MINIMUM_WP_VERSION
            );
            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
        }
    }
    Woo_Sham::instance();
}


?>