<?php

class BasbInit {

    protected static $_instance = null;

    static function get_instance() {
        if (self::$_instance == null) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    function __construct() {
        add_action('init', array($this, 'basb_init_register_styles'));
        add_action('init', array($this, 'basb_init_register_script'));
        add_action('admin_print_styles-settings_page_' . BASB_SETTINGS_OPTIONS_PAGE, array($this, 'basb_init_add_admin_css'));
        //add_action('admin_print_scripts-settings_page_' . BASB_SETTINGS_OPTIONS_PAGE, array($this, 'basb_init_add_admin_js'));
        // add_action('wp_enqueue_scripts', array($this, 'basb_ini_add_front_css_and_js'), 11);
        add_action('plugins_loaded', array($this, 'basb_init_plugins_loaded'));
        add_filter( 'plugin_action_links_' . BASB_PLUGIN_FILE, array($this,'basb_init_plugin_settings_link'));
    }

    function basb_init_plugin_settings_link( $links ) {
    $settings_link = '<a href="options-general.php?page='.BASB_SETTINGS_OPTIONS_PAGE.'">' . __( 'Settings', BASB_TEXT_DOMAIN ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

    
    function basb_init_plugins_loaded() {
        load_plugin_textdomain(BASB_TEXT_DOMAIN, false, dirname(BASB_PLUGIN_FILE) . '/languages/');
    }

    function basb_init_register_styles() {
        wp_register_style('basb_admin_css', BASB_PLUGIN_DIR_URL . 'css/basb_admin.css', '', BASB_PLUGIN_SCRIPT_VERSION);
        wp_register_style('basb_front_css', BASB_PLUGIN_DIR_URL . 'css/basb_front.css', '', BASB_PLUGIN_SCRIPT_VERSION);
    }

    function basb_init_register_script() {
        wp_enqueue_script('jquery');
        wp_register_script('basb_admin_js', BASB_PLUGIN_DIR_URL . 'js/basb_admin.js', array(
            'jquery'
                ), BASB_PLUGIN_SCRIPT_VERSION, true);
        wp_register_script('basb_front_js', BASB_PLUGIN_DIR_URL . 'js/basb_front.js', array(
            'jquery'
                ), BASB_PLUGIN_SCRIPT_VERSION, true);
    }

    function basb_init_add_admin_css() {
        wp_enqueue_style('basb_admin_css');
    }

    function basb_init_add_admin_js() {
        wp_enqueue_script('basb_admin_js');
    }

    function basb_ini_add_front_css_and_js() {
        
    }

    static function initAll() {
        BasbInit::get_instance();
        BasbAdmin::get_instance();
        BasbAdminMetabox::get_instance();
        BasbAdminSortcodeGenerator::get_instance();
        BasbShortcode::get_instance();
        BasbProductWidget::register_widget();
    }

}
