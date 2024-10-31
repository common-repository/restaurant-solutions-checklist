<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       stpetedesign.com
 * @since      1.0.0
 *
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Stp_Srtc
 * @subpackage Stp_Srtc/public
 * @author     stpetedesign.com <foucciano@gmail.com >
 */
class Stp_Srtc_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_filter( 'the_content', array($this,'get_checklist_template') ) ;
		add_action( 'wp_ajax_nopriv_update_checklist', array($this,'update_checklist') );
		add_action( 'wp_ajax_update_checklist', array($this,'update_checklist') );
		add_action( 'wp_ajax_nopriv_populate_checklist', array($this,'populate_checklist') );
		add_action( 'wp_ajax_populate_checklist', array($this,'populate_checklist') );
	
	}

	function populate_checklist(){
		$employee = Class_Stp_Strc_Staff::check_code(sanitize_text_field($_POST['code']));
		$checklist_id = isset($_POST['checklist_id']) ? sanitize_text_field($_POST['checklist_id']) : "";
		if($employee && $checklist_id != ""){
			$employee_id = $employee['employee_id'];
			$date = sanitize_text_field($_POST['date']);

			$row = $this->check_insert_or_update($employee_id, $checklist_id, $date);
			if($row != false){
				$items = $this->adding_md5(json_decode($row['selected_items']));
				echo json_encode(array('status'=>true, 'items'=>$items));
			}else{
				echo json_encode(array('status'=>true, 'items'=>array()));
			}
			
		}
		wp_die();
	}

	function adding_md5($items){
		$result  = array();
		foreach($items as $item){
			$result[md5($item->item)] = $item->item;
		}
		return $result;
	}

	function update_checklist(){
		$employee = Class_Stp_Strc_Staff::check_code(sanitize_text_field($_POST['code']));
		$checklist_id = isset($_POST['checklist_id']) ? sanitize_text_field($_POST['checklist_id']) : "";
		$date = (isset($_POST['date']) && $_POST['date'] !="") ? sanitize_text_field($_POST['date']): "";
		if($employee && $checklist_id != "" && $this->date_check($date)){
			$employee_id = $employee['employee_id'];
			$name = $employee['name'];
			
			$original_items = json_encode(get_post_meta($checklist_id, 'item',true));
			$selected_items = json_encode($_POST['items']);
			$data = array(
				'employee_id'=>sanitize_text_field($employee_id),
				'name'=>sanitize_text_field($name),
				'checklist_id'=>$checklist_id,
				'date'=>$date,
				'original_list'=>$original_items,
				'selected_items'=>$selected_items
			);
			$row_exist = $this->check_insert_or_update($employee_id, $checklist_id, $date);
			if($row_exist != false){
				echo $this->update_row_checklist($data);
			}else{
				echo $this->insert_row_checklist($data);
			}
			
			
		}else{

		}
		wp_die();
	}

	/**
	 * Makesure date is today or yesterday or tomorrow not ahead of that
	 */
	function date_check($date){
		$today = date('Y-m-d');
		$yesterday = date('Y-m-d',strtotime("-1 days"));
		$tomorrow = date('Y-m-d',strtotime("+1 days"));
		$day_before_yesterday = date('Y-m-d',strtotime("-2 days"));
		if($date != ""){
			if($date == $today || $date == $yesterday || $date == $tomorrow || $date == $day_before_yesterday){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}

	function get_checklist_template($content) {
		global $post;
		if ($post->post_type == 'stp_checklist') {
			wp_enqueue_style($this->plugin_name);
			wp_enqueue_script($this->plugin_name);
			ob_start();
			include 'partials/checklist_template.php';
			$output = ob_get_contents();
			ob_end_clean();
			$content = $output;
		}
		return $content;
	}

	function check_insert_or_update($employee_id, $checklist_id, $date){
		global $wpdb;
        $wpdb->show_errors = false;
		$table_name = $wpdb->prefix . 'checklist'; 
		$query = 'SELECT * FROM '.$table_name.' Where employee_id = "'.$employee_id.'" and checklist_id = "'.$checklist_id.'" and date = "'.$date.'"';
		$result = $wpdb->get_row($query,  ARRAY_A);
		if($result != null){
			return $result;
		}
		return false;
	}

	function insert_row_checklist($data){
			global $wpdb;
            $wpdb->show_errors = false;
            $table_name = $wpdb->prefix . 'checklist'; 
			$result = $wpdb->insert( $table_name, $data);
			if($wpdb->last_error !== ''){
				return json_encode(array('status'=>false, 'msg'=>'There was a problem in updating list'));
			}else{
				if($result){
					return json_encode(array('status'=>true,'msg'=>'List updated successfully'));
				}else{
					return json_encode(array('status'=>false,'msg'=>'There was a problem in updating list'));
				}
			}
	}

	function update_row_checklist($data){
		global $wpdb;
		$wpdb->show_errors = false;
		$table_name = $wpdb->prefix . 'checklist'; 

		$new_data = array(
			'original_list'=>$data['original_list'],
			'selected_items'=>$data['selected_items']
		);

		$where = array(
			'employee_id'=>$data['employee_id'],
			'checklist_id'=>$data['checklist_id'],
			'date'=>$data['date'],
		);

		$result = $wpdb->update( $table_name, $new_data, $where);
		if($wpdb->last_error !== ''){
			return json_encode(array('status'=>false, 'msg'=>'There was a problem in updating list'));
		}else{
			if($result){
				return json_encode(array('status'=>true,'msg'=>'List updated successfully'));
			}else{
				return json_encode(array('status'=>false,'msg'=>'There was a problem in updating list'));
			}
		}
	}

	
	

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/stp-srtc-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/stp-srtc-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name, 'stp_ajax_params', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );

	}

}
