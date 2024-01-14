<?php
/** 
* Plugin Name: MV Slider
* Plugin URI: https://wordpress.org/plugins/mv-slider/
* Description: MV Slider is a fully responsive and mobile friendly Coupon generator plugin.
* Version: 1.0.0
* Requires at least: 5.5
* Requires PHP: 5.6
* Author: Mehedi Hasan
* Author URI: www.mehediwebdev.com
* License: GPL3
* License URI: http://www.gnu.org/licenses/gpl.html
* Text Domain: mv-slider
* Domain Path: /languages
*/

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'This script cannot be accessed directly.' );
}


if( ! class_exists( 'MV_Slider' )){
	 class MV_Slider{
        function __construct(){
         $this->define_constants();
		 $this->load_textdomain();

		 require_once( MV_SLIDER_PATH . 'functions/functions.php' );

		 add_action('admin_menu', array( $this, 'add_menu') );

		 require_once( MV_SLIDER_PATH . 'post-types/class.mv-slider-cpt.php' );
		 $MV_Slider_Post_Type = new MV_Slider_Post_Type();

		 require_once( MV_SLIDER_PATH . 'class.mv-slider-settings.php' );
		 $MV_Slider_Settings = new MV_Slider_Settings();

		 require_once( MV_SLIDER_PATH . 'shortcodes/class.mv-slider-shortcode.php' );
         $MV_Slider_Shortcode = new MV_Slider_Shortcode();

		 add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ), 999 );
		 add_action('admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		
		

		}

		public function define_constants(){
			// define( 'COUPON_WHEEL_NAME', 'Coupon Wheel' );
			define( 'MV_SLIDER_VERSION', '1.0.0' );
			define( 'MV_SLIDER_PATH', plugin_dir_path(__FILE__) );
			define( 'MV_SLIDER_URL', plugin_dir_url(__FILE__ ) );
			define( 'MV_SLIDER_LANGUAGE_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		 }

		 public function activate(){
          update_option( 'rewrite_rules', '');
		 }

		 public function deactivate(){
          flush_rewrite_rules();
		  unregister_post_type( 'mv-slider' );
		 }

		 public function uninstall(){
               delete_option( 'mv_slider_options' );

			   $posts = get_posts(
				 array(
					'post_type'      => 'mv-slider',
					'number_posts'   => -1,
					'post_status'    => 'any'
				 )

			   );

			   foreach( $posts as $post ){
                   wp_delete_post( $post->ID, true );
			   }
		 }

		 public function load_textdomain(){
			load_plugin_textdomain(
               'mv-slider',
			   false,
			   dirname( plugin_basename( __FILE__ ) ) . '/languages'
			);
		 }

		 public function add_menu(){
			//add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
             add_menu_page(  				
				esc_html__( 'MV Slider Options', 'mv-slider' ),
				esc_html__( 'MV Slider', 'mv-slider' ),
				 'manage_options',
				 'mv_slider_admin',
				 array( $this, 'mv_slider_settings_page'),
				 'dashicons-format-gallery',
				
			);

			add_submenu_page(
				'mv_slider_admin', 
				esc_html__( 'Manage Slides', 'mv-slider' ),
				esc_html__( 'Manage Slides', 'mv-slider' ),
				'manage_options',
				'edit.php?post_type=mv-slider',
				null,
				null
			   );


			   add_submenu_page(
				'mv_slider_admin', 
				esc_html__( 'Add New Slides', 'mv-slider' ),
				esc_html__( 'Add New Slides', 'mv-slider' ),
				'manage_options',
				'post-new.php?post_type=mv-slider',
				null,
				null
			   );
		 }
  

		 public function mv_slider_settings_page(){

			if( ! current_user_can( 'manage_options' ) ) {
               return;
			}
			if( isset( $_GET['settings-updated'] ) ){
				add_settings_error( 'mv_slider_options', 'mv_slider_message', esc_html__( 'Settings Saved', 'mv-slider'), 'success' );
			}
			settings_errors( 'mv_slider_options' );
			
			require( MV_SLIDER_PATH . 'views/settings-page.php' );

		 }

        public function register_scripts(){
			wp_enqueue_style( 'mv-slider-main-css', MV_SLIDER_URL . 'vendor/flexslider/flexslider.css', array(), MV_SLIDER_VERSION, 'all' );
			wp_enqueue_script( 'mv-slider-main-jq', MV_SLIDER_URL . 'vendor/flexslider/jquery.flexslider-min.js', array( 'jquery' ), 'MV_SLIDER_VERSION', true );
			
		}

		public function register_admin_scripts(){
			global $typenow;
			if( $typenow == 'mv-slider'){
			 wp_enqueue_style( 'mv-slider-admin', MV_SLIDER_URL . 'assets/css/admin.css' );
			}
			

		}
		 
	 }
}



if(  class_exists( 'MV_Slider' )){
	
	register_activation_hook( __FILE__, array( 'MV_Slider', 'activate' ) );
	register_deactivation_hook( __FILE__, array( 'MV_Slider', 'deactivate' ) );
	register_uninstall_hook( __FILE__, array( 'MV_Slider', 'uninstall' ) );
	$mv_slider_obj = new MV_Slider();
}







