<?php
/**
 * Plugin Name: Elementor ACF Repeater Widget
 * Description: Custom Elementor widget to display ACF repeater fields with custom HTML templates
 * Version:     1.0.12
 * Author:      Dezefy
 * Author URI:  https://dezefy.com
 * Text Domain: elementor-acf-repeater
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main Elementor ACF Repeater Widget Class
 */
final class Elementor_ACF_Repeater_Widget {

    /**
     * Plugin Version
     */
    const VERSION = '1.0.12';

    /**
     * Minimum Elementor Version
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

    /**
     * Minimum PHP Version
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * Shortcode prefix for the widget
     */
    const SHORTCODE_PREFIX = 'acfr_';

    /**
     * Instance
     */
    private static $_instance = null;

    /**
     * Instance
     * 
     * Ensures only one instance of the class is loaded or can be loaded.
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action('plugins_loaded', [$this, 'init']);
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if Elementor is installed and activated
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_elementor']);
            return;
        }

        // Check for required Elementor version
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_elementor_version']);
            return;
        }

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        // Check if ACF is installed and activated
        if (!class_exists('ACF')) {
            add_action('admin_notices', [$this, 'admin_notice_missing_acf']);
            return;
        }

        // Add Widget
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
        
        // Register Widget Scripts
        add_action('elementor/frontend/after_register_scripts', [$this, 'widget_scripts']);
        
        // Register Widget Styles
        add_action('elementor/frontend/after_enqueue_styles', [$this, 'widget_styles']);
        
        // Register AJAX handler for getting repeater subfields
        add_action('wp_ajax_get_acf_repeater_subfields', [$this, 'get_acf_repeater_subfields']);
        
        // Add script variables for AJAX
        add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
    }

    /**
     * Admin notice for missing Elementor
     */
    public function admin_notice_missing_elementor() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-acf-repeater'),
            '<strong>' . esc_html__('Elementor ACF Repeater Widget', 'elementor-acf-repeater') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-acf-repeater') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for missing ACF
     */
    public function admin_notice_missing_acf() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: ACF */
            esc_html__('"%1$s" requires "%2$s" to be installed and activated.', 'elementor-acf-repeater'),
            '<strong>' . esc_html__('Elementor ACF Repeater Widget', 'elementor-acf-repeater') . '</strong>',
            '<strong>' . esc_html__('Advanced Custom Fields', 'elementor-acf-repeater') . '</strong>'
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for minimum Elementor version
     */
    public function admin_notice_minimum_elementor_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-acf-repeater'),
            '<strong>' . esc_html__('Elementor ACF Repeater Widget', 'elementor-acf-repeater') . '</strong>',
            '<strong>' . esc_html__('Elementor', 'elementor-acf-repeater') . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Admin notice for minimum PHP version
     */
    public function admin_notice_minimum_php_version() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
            /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-acf-repeater'),
            '<strong>' . esc_html__('Elementor ACF Repeater Widget', 'elementor-acf-repeater') . '</strong>',
            '<strong>' . esc_html__('PHP', 'elementor-acf-repeater') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    /**
     * Register Widgets
     */
    public function register_widgets() {
        // Include Widget file
        require_once(__DIR__ . '/widgets/acf-repeater-widget.php');

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor_ACF_Repeater());
    }

    /**
     * Register Widget Scripts
     */
    public function widget_scripts() {
        wp_register_script('elementor-acf-repeater', plugins_url('/assets/js/elementor-acf-repeater.js', __FILE__), ['jquery'], self::VERSION, true);
        wp_localize_script('elementor-acf-repeater', 'elementor_acf_repeater_vars', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('elementor_acf_repeater_nonce'),
            'prefix' => self::SHORTCODE_PREFIX,
        ]);
        wp_enqueue_script('elementor-acf-repeater');
    }

    /**
     * Register Widget Styles
     */
    public function widget_styles() {
        wp_register_style('elementor-acf-repeater', plugins_url('/assets/css/elementor-acf-repeater.css', __FILE__), [], self::VERSION);
        wp_enqueue_style('elementor-acf-repeater');
    }
    
    /**
     * Admin scripts for Elementor editor
     */
    public function admin_scripts() {
        // Only enqueue on Elementor editor pages
        if (in_array(get_current_screen()->id, ['toplevel_page_elementor', 'edit-elementor_library'])) {
            wp_localize_script('elementor-acf-repeater', 'elementor_acf_repeater_vars', [
                'nonce' => wp_create_nonce('elementor_acf_repeater_nonce'),
                'prefix' => self::SHORTCODE_PREFIX
            ]);
        }
    }
    
    /**
     * AJAX handler for getting repeater subfields
     */
    public function get_acf_repeater_subfields() {
        // Check nonce
        check_ajax_referer('elementor_acf_repeater_nonce', 'security');
        
        // Check permissions
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Permission denied');
            return;
        }
        
        // Get repeater field name
        $repeater_field = isset($_POST['field']) ? sanitize_text_field($_POST['field']) : '';
        
        if (empty($repeater_field)) {
            wp_send_json_error('No field specified');
            return;
        }
        
        // Get subfields
        $subfields = $this->get_repeater_subfields($repeater_field);
        
        wp_send_json_success($subfields);
    }
    
    /**
     * Get all subfields of a repeater field
     */
    private function get_repeater_subfields($repeater_name) {
        $subfields = [];
        
        // Check if ACF is active
        if (!function_exists('acf_get_field_groups')) {
            return $subfields;
        }
        
        $field_groups = acf_get_field_groups();
        
        foreach ($field_groups as $field_group) {
            $fields = acf_get_fields($field_group);
            
            if (!$fields) {
                continue;
            }
            
            foreach ($fields as $field) {
                if ($field['type'] === 'repeater' && $field['name'] === $repeater_name) {
                    if (isset($field['sub_fields']) && is_array($field['sub_fields'])) {
                        foreach ($field['sub_fields'] as $sub_field) {
                            $subfields[] = $sub_field['name'];
                        }
                    }
                    break 2;
                }
            }
        }
        
        return $subfields;
    }
}

// Initialize the plugin
Elementor_ACF_Repeater_Widget::instance();