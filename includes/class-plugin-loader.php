<?php 


class WebMi_Speed_Up {
 
    protected $loader;
    protected $plugin_slug;
 
    public function __construct() {
 
        $this->plugin_slug = 'webmi-speed-up';
 
        $this->load_plugins();

        add_action('admin_init', array(&$this, 'remove_admin_menus'));
 
    }
 
    private function load_plugins() {
 
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/autoptimize/autoptimize.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/heartbeat-control/heartbeat-control.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/hide-plugin-updates-notifications/wphdpuw.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/redis-cache/redis-cache.php';
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/wp-super-cache/wp-cache.php';

    }

    public function remove_admin_menus() {
        remove_menu_page( 'wphpuw' );
    }
 
 
}