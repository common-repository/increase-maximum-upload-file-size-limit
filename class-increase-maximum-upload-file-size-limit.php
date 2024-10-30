<?php
namespace JLTIUSL;

use JLTIUSL\Libs\Assets;
use JLTIUSL\Libs\Helper;
use JLTIUSL\Libs\Featured;
use JLTIUSL\Inc\Classes\Recommended_Plugins;
use JLTIUSL\Inc\Classes\Notifications\Notifications;
use JLTIUSL\Inc\Classes\Pro_Upgrade;
use JLTIUSL\Inc\Classes\Row_Links;
use JLTIUSL\Inc\Classes\Upgrade_Plugin;
use JLTIUSL\Inc\Classes\Feedback;
use JLTIUSL\Inc\Admin\AdminSettings;

/**
 * Main Class
 *
 * @increase-maximum-upload-file-size-limit
 * Jewel Theme <support@jeweltheme.com>
 * @version     1.0.1
 */

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * JLT_IUSL Class
 */
if ( ! class_exists( '\JLTIUSL\JLT_IUSL' ) ) {

	/**
	 * Class: JLT_IUSL
	 */
	final class JLT_IUSL {

		const VERSION            = JLTIUSL_VER;
		private static $instance = null;

		/**
		 * what we collect construct method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			
			$this->includes();

			add_action( 'plugins_loaded', array( $this, 'jltiusl_plugins_loaded' ), 999 );
			// Body Class.
			add_filter( 'admin_body_class', array( $this, 'jltiusl_body_class' ) );

			// This should run earlier .
			// add_action( 'plugins_loaded', [ $this, 'jltiusl_maybe_run_upgrades' ], -100 ); .
		}


		/**
		 * Initialization
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
        public function jltiusl_init(){
        	$this->jltiusl_load_textdomain();

			if( ! empty($value) ){
				add_filter('upload_size_limit', [$this, 'jltiusl_value']);
			}
        }

	    /**
	     * Convert Memory Size
	     *
	     * @param [type] $size
	     *
	     * @return void
	     */
	    public function convert_memory_size($size)
	    {

	        $l = substr($size, -1);
	        $ret = substr($size, 0, -1);

	        switch (strtoupper($l)) {
	            case 'P':
	                $ret *= 1024;
	            case 'T':
	                $ret *= 1024;
	            case 'G':
	                $ret *= 1024;
	            case 'M':
	                $ret *= 1024;
	            case 'K':
	                $ret *= 1024;
	        }

	        return $ret;
	    }



		// IUSL Settings Data
        public function jltiusl_value(){
        	$settings = jltiusl_option('jltiusl', 'size_in_mb');
			$value = !empty( $settings ) ? esc_attr( $settings ) : '';
			$memory_usage_convert = round($value * 1024 * 1024 * 10);
        	return $this->convert_memory_size($memory_usage_convert);
        }



		/**
		 * plugins_loaded method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltiusl_plugins_loaded() {
			$this->jltiusl_activate();
		}

		/**
		 * Version Key
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function plugin_version_key() {
			return Helper::jltiusl_slug_cleanup() . '_version';
		}

		/**
		 * Activation Hook
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public static function jltiusl_activate() {
			$current_jltiusl_version = get_option( self::plugin_version_key(), null );

			if ( get_option( 'jltiusl_activation_time' ) === false ) {
				update_option( 'jltiusl_activation_time', strtotime( 'now' ) );
			}

			if ( is_null( $current_jltiusl_version ) ) {
				update_option( self::plugin_version_key(), self::VERSION );
			}

			$allowed = get_option( Helper::jltiusl_slug_cleanup() . '_allow_tracking', 'no' );

			// if it wasn't allowed before, do nothing .
			if ( 'yes' !== $allowed ) {
				return;
			}
			// re-schedule and delete the last sent time so we could force send again .
			$hook_name = Helper::jltiusl_slug_cleanup() . '_tracker_send_event';
			if ( ! wp_next_scheduled( $hook_name ) ) {
				wp_schedule_event( time(), 'weekly', $hook_name );
			}
		}


		/**
		 * Add Body Class
		 *
		 * @param [type] $classes .
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltiusl_body_class( $classes ) {
			$classes .= ' increase-maximum-upload-file-size-limit ';
			return $classes;
		}

		/**
		 * Run Upgrader Class
		 *
		 * @return void
		 */
		public function jltiusl_maybe_run_upgrades() {
			if ( ! is_admin() && ! current_user_can( 'manage_options' ) ) {
				return;
			}

			// Run Upgrader .
			$upgrade = new Upgrade_Plugin();

			// Need to work on Upgrade Class .
			if ( $upgrade->if_updates_available() ) {
				$upgrade->run_updates();
			}
		}


		/**
		 * Include methods
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function includes() {
			new AdminSettings();
			new Assets();
			new Recommended_Plugins();
			new Row_Links();
			new Pro_Upgrade();
			new Notifications();
			new Featured();
			new Feedback();
		}


		/**
		 * Text Domain
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function jltiusl_load_textdomain() {
			$domain = 'increase-maximum-upload-file-size-limit';
			$locale = apply_filters( 'jltiusl_plugin_locale', get_locale(), $domain );

			load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
			load_plugin_textdomain( $domain, false, dirname( JLTIUSL_BASE ) . '/languages/' );
		}


		/**
		 * Returns the singleton instance of the class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof JLT_IUSL ) ) {
				self::$instance = new JLT_IUSL();
				self::$instance->jltiusl_init();
			}

			return self::$instance;
		}
	}

	// Get Instant of JLT_IUSL Class .
	JLT_IUSL::get_instance();
}