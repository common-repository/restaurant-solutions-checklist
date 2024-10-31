<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       stpetedesign.com
 * @since      1.0.0
 *
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/admin
 * @author     stpetedesign.com <foucciano@gmail.com >
 */
class Stp_Srtc_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->register_menu();		
	}

	
	public function register_menu(){
		$obj =	new Stp_Srtc_Menu($this->plugin_name, $this->version);	
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Stp_Srtc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Stp_Srtc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/stp-srtc-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Stp_Srtc_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Stp_Srtc_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_localize_script( $this->plugin_name, 'stp_ajax_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

}
