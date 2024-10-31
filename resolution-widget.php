<?php
/**
 * Plugin Name: Resolution1 (R1) Contact Widget
 * Plugin URI:
 * Description: Converts any static contact us page into a dynamic call-center like experience, by allowing your website visitors to conveniently connect with your business from any page using the pop-up contact us button.
 * Version: 2.0.0
 * Author: flooidCX
 * Author URI: https://flooidcx.com/
 * Requires at least: 4.4.0
 * Tested up to: 5.7
 *
 * Text Domain: 'resolution-widget'
 * Domain Path: /languages/
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package Resolution_Widget
 * @author  flooidCX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/*
 * Globals constants.
 */
define( 'RESOLUTION_WIDGET_MIN_PHP_VER',   '5.6.0' );
define( 'RESOLUTION_WIDGET_MIN_WP_VER',    '4.4.0' );
define( 'RESOLUTION_WIDGET_VER',           '1.0.0' );
define( 'RESOLUTION_WIDGET_ROOT_URL',      plugin_dir_url( __FILE__ ) );
define( 'RESOLUTION_WIDGET_ROOT_PATH',     plugin_dir_path( __FILE__ ) );


/**
 * The main class.
 *
 * @since 1.0.0
 */
class Resolution_Widget {

		/**
		 * The singelton instance of Resolution_Widget.
		 *
		 * @since 1.0.0
		 *
		 * @var Resolution_Widget
		 */
		private static $Resolution1WP_instance = null;

		/**
		 * Returns the singelton instance of Resolution_Widget.
		 *
		 * Ensures only one instance of Resolution_Widget is/can be loaded.
		 *
		 * @since 1.0.0
		 *
		 * @return Resolution_Widget
		 */
		public static function Resolution1WP_get_instance() {
			if ( null === self::$Resolution1WP_instance ) {
				self::$Resolution1WP_instance = new self();
			}

			return self::$Resolution1WP_instance;
		}

		/**
		 * The constructor.
		 *
		 * Private constructor to make sure it can not be called directly from outside the class.
		 *
		 * @since 1.0.0
		 * 
		 * @return void
		 */
		private function __construct() {

			$this->Resolution1WP_includes();
			$this->Resolution1WP_hooks();

			/**
			 * The resolution_widget_loaded hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'resolution_widget_loaded' );
		}

		/**
		 * Includes the required files.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function Resolution1WP_includes() {
			// Global includes.
            include_once( RESOLUTION_WIDGET_ROOT_PATH . 'includes/class-r1-widget.php' );
            // include_once( RESOLUTION_WIDGET_ROOT_PATH . 'settings.php' );

			if ( is_admin() ) {
				// Back-end only includes.
				include_once( RESOLUTION_WIDGET_ROOT_PATH . 'includes/admin/class-r1-widget-admin-notices.php' );
			}
		}

		/**
		 * Plugin hooks.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function Resolution1WP_hooks() {
			// Actions
			add_action( 'widgets_init', array( $this, 'Resolution1WP_register_widget' ) );
		}

		/**
		 * Register custom widget.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function Resolution1WP_register_widget() {
			register_widget( 'Resolution1WP_Widget' );
		}

		/**
		 * On plugin activation.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function Resolution1WP_activate() {
			// Nothing To Do for Now.
		}

		/**
		 * On plugin deactivation.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function Resolution1WP_deactivate() {
			// Nothing To Do for Now.
		}

		/**
		 * On plugin uninstall.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public static function Resolution1WP_uninstall() {
			include_once( RESOLUTION_WIDGET_ROOT_PATH . 'uninstall.php' );
		}
}


/**
 * The main instance of Resolution_Widget.
 *
 * Returns the main instance of Resolution_Widget.
 *
 * @since 1.0.0
 *
 * @return Resolution_Widget
 */
function Resolution1WP_resolution_widget() {
	return Resolution_Widget::Resolution1WP_get_instance();
}

// Global for backwards compatibility.
$GLOBALS['resolution_widget'] = Resolution1WP_resolution_widget();

register_activation_hook( __FILE__, array( 'Resolution_Widget', 'Resolution1WP_activate' ) );
register_deactivation_hook( __FILE__, array( 'Resolution_Widget', 'Resolution1WP_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'Resolution_Widget', 'Resolution1WP_uninstall' ) );
