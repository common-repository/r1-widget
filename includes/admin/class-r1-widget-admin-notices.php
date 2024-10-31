<?php
/**
 * The Resolution1WP_Widget_Admin_Notices class.
 *
 * @package Resolution_Widget/Admin
 * @author  flooidCX
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
	 * Admin notices.
	 *
	 * Handles admin notices.
	 *
	 * @since 1.0.0
	 */
class Resolution1WP_Widget_Admin_Notices {
		/**
		 * Notices array.
		 *
		 * @var array
		 */
		public $notices = array();

		/**
		 * The constructor.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'admin_notices', array( $this, 'Resolution1WP_admin_notices' ) );
			add_action( 'wp_loaded', array( $this, 'Resolution1WP_hide_notices' ) );
		}

		/**
		 * Adds slug keyed notices (to avoid duplication).
		 *
		 * @since 1.0.0
		 *
		 * @param string $slug        Notice slug.
		 * @param string $class       CSS class.
		 * @param string $message     Notice body.
		 * @param bool   $dismissible Optional. Allow/disallow dismissing the notice. Default false.
		 *
		 * @return void
		 */
		public function Resolution1WP_add_admin_notice( $slug, $class, $message, $dismissible = false ) {
			$this->notices[ $slug ] = array(
				'class'       => $class,
				'message'     => $message,
				'dismissible' => $dismissible,
			);
		}

		/**
		 * Displays the notices.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function Resolution1WP_admin_notices() {
			// Exit if user has no privilges.
			if ( ! current_user_can( 'administrator' ) ) {
				return;
			}

			// Basic checks.
			$this->Resolution1WP_check_environment();

			// Display the notices collected so far.
			foreach ( (array) $this->notices as $notice_key => $notice ) {
				echo '<div class="' . esc_attr( $notice['class'] ) . '" style="position:relative;">';

				if ( $notice['dismissible'] ) {
					echo '<a href="' . esc_url( wp_nonce_url( add_query_arg( 'r1-widget-hide-notice', $notice_key ), 'r1-widget_hide_notices_nonce', '_r1-widget_notice_nonce' ) ) . '" class="woocommerce-message-close notice-dismiss" style="position:absolute;right:1px;padding:9px;text-decoration:none;"></a>';
				}

				echo '<p>' . wp_kses( $notice['message'], array( 'a' => array( 'href' => array() ) ) ) . '</p>';
				
				echo '</div>';
			}
		}

		/**
		 * Handles all the basic checks.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function Resolution1WP_check_environment() {
			$show_phpver_notice = get_option( 'r1-widget_show_phpver_notice' );
			$show_wpver_notice  = get_option( 'r1-widget_show_wpver_notice' );

			if ( empty( $show_phpver_notice ) ) {
				if ( version_compare( phpversion(), RESOLUTION_WIDGET_MIN_PHP_VER, '<' ) ) {
					/* translators: 1) int version 2) int version */
					$message = __( 'Resolution Widget - The minimum PHP version required for this plugin is %1$s. You are running %2$s.', 'resolution-widget' );
					$this->Resolution1WP_add_admin_notice( 'phpver', 'error', sprintf( $message, RESOLUTION_WIDGET_MIN_PHP_VER, phpversion() ), true );
				}
			}

			if ( empty( $show_wpver_notice ) ) {
				global $wp_version;

				if ( version_compare( $wp_version, RESOLUTION_WIDGET_MIN_WP_VER, '<' ) ) {
					/* translators: 1) int version 2) int version */
					$message = __( 'Resolution Widget - The minimum WordPress version required for this plugin is %1$s. You are running %2$s.', 'resolution-widget' );
					$this->Resolution1WP_add_admin_notice( 'wpver', 'notice notice-warning', sprintf( $message, RESOLUTION_WIDGET_MIN_WP_VER, WC_VERSION ), true );
				}
			}

		}

		/**
		 * Hides any admin notices.
		 *
		 * @since 1.0.0
		 *
		 * @return void
		 */
		public function Resolution1WP_hide_notices() {
			if ( isset( $_GET['r1-widget-hide-notice'] ) && isset( $_GET['_r1-widget_notice_nonce'] ) ) {
				if ( ! wp_verify_nonce( $_GET['_r1-widget_notice_nonce'], 'r1-widget_hide_notices_nonce' ) ) {
					wp_die( __( 'Action failed. Please refresh the page and retry.', 'resolution-widget' ) );
				}

				if ( ! current_user_can( 'administrator' ) ) {
					wp_die( __( 'Cheatin&#8217; huh?', 'resolution-widget' ) );
				}

				$notice = wc_clean( $_GET['r1-widget-hide-notice'] );

				switch ( $notice ) {
					case 'phpver' :
						update_option( 'r1-widget_show_phpver_notice', 'no' );
						break;
					case 'wpver' :
						update_option( 'r1-widget_show_wpver_notice', 'no' );
						break;
				}
			}
		}
}

new Resolution1WP_Widget_Admin_Notices();
