<?php
namespace JLTIUSL\Inc\Admin;

// No, Direct access Sir !!!
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @version 1.0.1
 * @package increase-maximum-upload-file-size-limit
 */
if ( ! class_exists( 'AdminSettings' ) ) {
	/**
	 * Admin Settings Class
	 */
	class AdminSettings {


		private static $settings_api = null;

		/**
		 * Adminsettings Construct method
		 */
		public function __construct() {
			add_action( 'admin_init', array( $this, 'settings_fields' ) );
			add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		}

		/**
		 * Register Main Menu.
		 *
		 * @return void
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function settings_menu() {
			add_menu_page(
				__( 'Increase Upload Size Limit', 'increase-maximum-upload-file-size-limit' ),
				__( 'Upload Size', 'increase-maximum-upload-file-size-limit' ),
				'manage_options',
				'increase-maximum-upload-file-size-limit-settings',
				array( $this, 'settings_page' ),
				'dashicons-admin-generic',
				40
			);
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return void
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function settings_fields() {
			$sections = array(
				array(
					'id'    => 'jltiusl',
					'title' => '',
				),
			);

			$settings_fields = array(
				'jltiusl'    => array(
					array(
						'name'              => 'size_in_mb',
						'label'             => __( 'Upload File Size', 'increase-maximum-upload-file-size-limit' ),
						'desc'              => __( '(Enter the numeric value in MB(Megabytes). Example - 512MB, for 2GB - 2048MB) ', 'increase-maximum-upload-file-size-limit' ),
						'placeholder'       => __( '32', 'increase-maximum-upload-file-size-limit' ),
						// 'min'               => 0,
						// 'max'               => 100,
						// 'step'              => '0.01',
						'type'              => 'number',
						'default'           => 'Title',
						'sanitize_callback' => 'floatval',
					)
				)
			);

			self::$settings_api = new Settings_API();

			/*
			 * set the settings.
			 */
			self::$settings_api->set_sections( $sections );
			self::$settings_api->set_fields( $settings_fields );

			/*
			 * initialize settings
			 */
			self::$settings_api->admin_init();
		}

		/**
		 * Settings page
		 *
		 * @return void
		 *
		 * @author Jewel Theme <support@jeweltheme.com>
		 */
		public function settings_page() {                            ?>

			<div class="wrap jltiusl-settings-page">
				<h2 style="display: flex;"><?php esc_html_e( 'Increase Upload Size Settings', 'increase-maximum-upload-file-size-limit' ); ?>
					<span id="changelog_badge"></span>
				</h2>
				<?php self::$settings_api->show_settings(); ?>
			</div>
			<?php
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		public function get_pages() {
			$pages         = get_pages();
			$pages_options = array();

			if ( $pages ) {
				foreach ( $pages as $page ) {
					$pages_options[ $page->ID ] = $page->post_title;
				}
			}

			return $pages_options;
		}
	}
}