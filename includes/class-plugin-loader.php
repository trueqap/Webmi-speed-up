<?php 


class WebMi_Speed_Up {
 
    protected $loader;
    protected $plugin_slug;
 
    public function __construct() {
 
        $this->plugin_slug = 'webmi-speed-up';
 
        $this->load_plugins();
        $this->remove_wordpress_functions();

        //WORDPRESS
        add_action('admin_init', array(&$this, 'remove_admin_menus'));
        add_action('init', array(&$this, 'remove_heartbeat'));
        add_action('script_loader_src', array(&$this, 'remove_script_version'));
        add_action('style_loader_src', array(&$this, 'remove_script_version'));
        add_action('pre_ping', array(&$this, 'remove_pingback'));
        add_action('wp_enqueue_scripts', array(&$this, 'remove_dequeue_dashicon'));
        add_action('comment_form_default_fields', array(&$this, 'remove_comment_url'));
        add_action('init', array(&$this, 'remove_emojis'));
        add_action('tiny_mce_plugins', array(&$this, 'remove_emojis_tinymce'));
        add_action('init', array(&$this, 'remove_loading_wp_embed'));

        //WOOCMMERCE
        add_action('wp_enqueue_scripts', array(&$this, 'remove_woocommerce_cart_fragments'));
        add_action('wp_enqueue_scripts', array(&$this, 'remove_woocommerce_styles_scripts'));
    }
 
        private function load_plugins() {
     
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/autoptimize/autoptimize.php';
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/hide-plugin-updates-notifications/wphdpuw.php';
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/redis-cache/redis-cache.php';
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'plugins/wp-super-cache/wp-cache.php';

        }

// Remove the heartbeat completely 
        public function remove_heartbeat() {
            wp_deregister_script('heartbeat');
        }

// Remove the hide-plugin-updates-notifications menu from admin
        public function remove_admin_menus() {
            remove_menu_page( 'wphpuw' );
        }

// Remove the script versions
        public function remove_script_version( $src ) {
            if( strpos( $src, '?ver=' ) )
             $src = remove_query_arg( 'ver', $src );
            return $src;
        }

//Remove WordPress functions
        public function remove_wordpress_functions() {
            remove_action( 'wp_head', 'rsd_link' ) ;
            remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
            add_filter('xmlrpc_enabled', '__return_false');
            remove_action( 'wp_head', 'wp_generator' ) ;
            remove_action( 'wp_head', 'wlwmanifest_link' ) ;
        }

        public function remove_pingback( &$links ) {
            foreach ( $links as $l => $link )
            if ( 0 === strpos( $link, get_option( 'home' ) ) )
            unset($links[$l]);
        }

//Remove Dashicons on Front-end    
        public function remove_dequeue_dashicon() {
            if (current_user_can( 'update_core' )) {
              return;
             }
            wp_deregister_style('dashicons');
         }

//Remove URL from WordPress Comment forms
        public function remove_comment_url($fields) { 
            unset($fields['url']);
            return $fields;
        }

//Remove emojis
       public function remove_emojis() {
            remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
            remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
            remove_action( 'wp_print_styles', 'print_emoji_styles' );
            remove_action( 'admin_print_styles', 'print_emoji_styles' );    
            remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
            remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );  
            remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
         }

        public function remove_emojis_tinymce( $plugins ) {
            if ( is_array( $plugins ) ) {
                return array_diff( $plugins, array( 'wpemoji' ) );
            } else {
                return array();
            }
        }

// Remove WP embed script
        public function remove_loading_wp_embed() {
            if (!is_admin()) {
            wp_deregister_script('wp-embed');
            }
        }



////////// WOOOCOMMERCE 



// Remove Ajax Call from WooCommerce on front page and posts

    public function remove_woocommerce_cart_fragments() {
        if (is_front_page() || is_single() ) wp_dequeue_script('wc-cart-fragments');
    }
 
// Remove All WooCommerce Styles and Scripts Except Shop Pages

    public function remove_woocommerce_styles_scripts() {
        if ( function_exists( 'is_woocommerce' ) ) {
            if ( ! is_woocommerce() && ! is_cart() && ! is_checkout() ) {

                # Styles
                wp_dequeue_style( 'woocommerce-general' );
                wp_dequeue_style( 'woocommerce-layout' );
                wp_dequeue_style( 'woocommerce-smallscreen' );
                wp_dequeue_style( 'woocommerce_frontend_styles' );
                wp_dequeue_style( 'woocommerce_fancybox_styles' );
                wp_dequeue_style( 'woocommerce_chosen_styles' );
                wp_dequeue_style( 'woocommerce_prettyPhoto_css' );

                # Scripts
                wp_dequeue_script( 'wc_price_slider' );
                wp_dequeue_script( 'wc-single-product' );
                wp_dequeue_script( 'wc-add-to-cart' );
                wp_dequeue_script( 'wc-cart-fragments' );
                wp_dequeue_script( 'wc-checkout' );
                wp_dequeue_script( 'wc-add-to-cart-variation' );
                wp_dequeue_script( 'wc-single-product' );
                wp_dequeue_script( 'wc-cart' );
                wp_dequeue_script( 'wc-chosen' );
                wp_dequeue_script( 'woocommerce' );
                wp_dequeue_script( 'prettyPhoto' );
                wp_dequeue_script( 'prettyPhoto-init' );
                wp_dequeue_script( 'jquery-blockui' );
                wp_dequeue_script( 'jquery-placeholder' );
                wp_dequeue_script( 'fancybox' );
                wp_dequeue_script( 'jqueryui' );
            }
        }
    }
}