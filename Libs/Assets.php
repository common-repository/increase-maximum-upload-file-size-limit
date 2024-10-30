<?php
namespace JLTIUSL\Libs;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Assets' ) ) {

	/**
	 * Assets Class
	 *
	 * Jewel Theme <support@jeweltheme.com>
	 * @version     1.0.1
	 */
	class Assets {

		/**
		 * Constructor method
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function __construct() {
			// add_action( 'admin_enqueue_scripts', array( $this, 'jltiusl_admin_enqueue_scripts' ), 100 );
		}


		/**
		 * Get environment mode
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function get_mode() {
			return defined( 'WP_DEBUG' ) && WP_DEBUG ? 'development' : 'production';
		}


		/**
		 * Enqueue Scripts
		 *
		 * @method admin_enqueue_scripts()
		 */
		public function jltiusl_admin_enqueue_scripts() {
			// CSS Files .
			wp_enqueue_style( 'increase-maximum-upload-file-size-limit-admin', JLTIUSL_ASSETS . 'css/increase-maximum-upload-file-size-limit-admin.css', array( 'dashicons' ), JLTIUSL_VER, 'all' );

			// JS Files .
			wp_enqueue_script( 'increase-maximum-upload-file-size-limit-admin', JLTIUSL_ASSETS . 'js/increase-maximum-upload-file-size-limit-admin.js', array( 'jquery' ), JLTIUSL_VER, true );
			wp_localize_script(
				'increase-maximum-upload-file-size-limit-admin',
				'JLTIUSLCORE',
				array(
					'admin_ajax'        => admin_url( 'admin-ajax.php' ),
					'recommended_nonce' => wp_create_nonce( 'jltiusl_recommended_nonce' ),
				)
			);
		}
	}
}