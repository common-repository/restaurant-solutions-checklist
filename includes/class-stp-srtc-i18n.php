<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       stpetedesign.com
 * @since      1.0.0
 *
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/includes
 * @author     stpetedesign.com <foucciano@gmail.com >
 */
class Stp_Srtc_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'stp-srtc',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
