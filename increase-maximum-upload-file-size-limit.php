<?php
/**
 * Plugin Name: Increase Maximum Upload file Size Limit
 * Plugin URI:  http://jeweltheme.com/
 * Description: Easiest way to increase Maximum Upload File Size Limit
 * Version:     1.0.1
 * Author:      Jewel Theme
 * Author URI:  https://jeweltheme.com
 * Text Domain: increase-maximum-upload-file-size-limit
 * Domain Path: languages/
 * License:     GPLv3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package increase-maximum-upload-file-size-limit
 */

/*
 * don't call the file directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	wp_die( esc_html__( 'You can\'t access this page', 'increase-maximum-upload-file-size-limit' ) );
}

$jltiusl_plugin_data = get_file_data(
	__FILE__,
	array(
		'Version'     => 'Version',
		'Plugin Name' => 'Plugin Name',
		'Author'      => 'Author',
		'Description' => 'Description',
		'Plugin URI'  => 'Plugin URI',
	),
	false
);

// Define Constants.
if ( ! defined( 'JLTIUSL' ) ) {
	define( 'JLTIUSL', $jltiusl_plugin_data['Plugin Name'] );
}

if ( ! defined( 'JLTIUSL_VER' ) ) {
	define( 'JLTIUSL_VER', $jltiusl_plugin_data['Version'] );
}

if ( ! defined( 'JLTIUSL_AUTHOR' ) ) {
	define( 'JLTIUSL_AUTHOR', $jltiusl_plugin_data['Author'] );
}

if ( ! defined( 'JLTIUSL_DESC' ) ) {
	define( 'JLTIUSL_DESC', $jltiusl_plugin_data['Author'] );
}

if ( ! defined( 'JLTIUSL_URI' ) ) {
	define( 'JLTIUSL_URI', $jltiusl_plugin_data['Plugin URI'] );
}

if ( ! defined( 'JLTIUSL_DIR' ) ) {
	define( 'JLTIUSL_DIR', __DIR__ );
}

if ( ! defined( 'JLTIUSL_FILE' ) ) {
	define( 'JLTIUSL_FILE', __FILE__ );
}

if ( ! defined( 'JLTIUSL_SLUG' ) ) {
	define( 'JLTIUSL_SLUG', dirname( plugin_basename( __FILE__ ) ) );
}

if ( ! defined( 'JLTIUSL_BASE' ) ) {
	define( 'JLTIUSL_BASE', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'JLTIUSL_PATH' ) ) {
	define( 'JLTIUSL_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
}

if ( ! defined( 'JLTIUSL_URL' ) ) {
	define( 'JLTIUSL_URL', trailingslashit( plugins_url( '/', __FILE__ ) ) );
}

if ( ! defined( 'JLTIUSL_INC' ) ) {
	define( 'JLTIUSL_INC', JLTIUSL_PATH . '/Inc/' );
}

if ( ! defined( 'JLTIUSL_LIBS' ) ) {
	define( 'JLTIUSL_LIBS', JLTIUSL_PATH . 'Libs' );
}

if ( ! defined( 'JLTIUSL_ASSETS' ) ) {
	define( 'JLTIUSL_ASSETS', JLTIUSL_URL . 'assets/' );
}

if ( ! defined( 'JLTIUSL_IMAGES' ) ) {
	define( 'JLTIUSL_IMAGES', JLTIUSL_ASSETS . 'images' );
}

if ( ! class_exists( '\\JLTIUSL\\JLT_IUSL' ) ) {
	// Autoload Files.
	include_once JLTIUSL_DIR . '/vendor/autoload.php';
	// Instantiate JLT_IUSL Class.
	include_once JLTIUSL_DIR . '/class-increase-maximum-upload-file-size-limit.php';
}