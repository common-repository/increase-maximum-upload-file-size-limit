<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * @version       1.0.0
 * @package       JLT_IUSL
 * @license       Copyright JLT_IUSL
 */

if ( ! function_exists( 'jltiusl_option' ) ) {
	/**
	 * Get setting database option
	 *
	 * @param string $section default section name jltiusl_general .
	 * @param string $key .
	 * @param string $default .
	 *
	 * @return string
	 */
	function jltiusl_option( $section = 'jltiusl_general', $key = '', $default = '' ) {
		$settings = get_option( $section );

		return isset( $settings[ $key ] ) ? $settings[ $key ] : $default;
	}
}

if ( ! function_exists( 'jltiusl_exclude_pages' ) ) {
	/**
	 * Get exclude pages setting option data
	 *
	 * @return string|array
	 *
	 * @version 1.0.0
	 */
	function jltiusl_exclude_pages() {
		return jltiusl_option( 'jltiusl_triggers', 'exclude_pages', array() );
	}
}

if ( ! function_exists( 'jltiusl_exclude_pages' ) ) {
	/**
	 * Get exclude pages except setting option data
	 *
	 * @return string|array
	 *
	 * @version 1.0.0
	 */
	function jltiusl_exclude_pages_except() {
		return jltiusl_option( 'jltiusl_triggers', 'exclude_pages_except', array() );
	}
}