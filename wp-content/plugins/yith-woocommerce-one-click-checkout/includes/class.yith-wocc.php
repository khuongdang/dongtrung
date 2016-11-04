<?php
/**
 * Main class
 *
 * @author Yithemes
 * @package YITH WooCommerce One-Click Checkout
 * @version 1.0.0
 */


if ( ! defined( 'YITH_WOCC' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WOCC' ) ) {
	/**
	 * YITH WooCommerce One-Click Checkout
	 *
	 * @since 1.0.0
	 */
	class YITH_WOCC {

		/**
		 * Single instance of the class
		 *
		 * @var \YITH_WOCC
		 * @since 1.0.0
		 */
		protected static $instance;

		/**
		 * Plugin version
		 *
		 * @var string
		 * @since 1.0.0
		 */
		public $version = YITH_WOCC_VERSION;


		/**
		 * Returns single instance of the class
		 *
		 * @return \YITH_WOCC
		 * @since 1.0.0
		 */
		public static function get_instance(){
			if( is_null( self::$instance ) ){
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @return mixed YITH_WOCC_Admin | YITH_WOCC_Frontend
		 * @since 1.0.0
		 */
		public function __construct() {
			// Class admin
			if ( is_admin() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['context'] ) && $_REQUEST['context'] == 'frontend' ) ) {
				// Load Plugin Framework
				add_action( 'after_setup_theme', array( $this, 'plugin_fw_loader' ), 1 );
				
				YITH_WOCC_Admin();
			}
			else {
				// Class Frontend
				YITH_WOCC_Frontend();
			}

		}

		/**
		 * Load Plugin Framework
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 * @author Andrea Grillo <andrea.grillo@yithemes.com>
		 */
		public function plugin_fw_loader() {

			if ( ! defined( 'YIT' ) || ! defined( 'YIT_CORE_PLUGIN' ) ) {
				require_once( YITH_WOCC_DIR . '/plugin-fw/yit-plugin.php' );
			}

		}
	}
}

/**
 * Unique access to instance of YITH_WOCC class
 *
 * @return \YITH_WOCC
 * @since 1.0.0
 */
function YITH_WOCC(){
	return YITH_WOCC::get_instance();
}