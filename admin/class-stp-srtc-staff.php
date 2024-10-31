<?php

class Class_Stp_Strc_Staff{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "Home";

    private $setting_key = 'staff_settting';

   

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),1);
        

        add_action( 'wp_ajax_create_employee', array($this,'create_employee') );
        add_action( 'wp_ajax_get_all_employee', array($this,'get_all_employee') );
        add_action( 'wp_ajax_change_state', array($this,'change_state') );
        add_action( 'wp_ajax_delete_employee', array($this,'delete_employee') );
        add_action( 'wp_ajax_edit_employee', array($this,'edit_employee') );
   
    }

    
    

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
            <?php _e( $this->tab_name, 'http2-push-content' ); ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
       <script id="employee_template" type="text/x-jsrender">
           <tr id="employee_{{:employee_id}}" data-id="{{:employee_id}}" data-name="{{:name}}" data-code="{{:code}}"><td>{{:employee_id}}</td><td class="name">{{:name}}</td><td class="code">{{:code}}</td><td>{{if status== 1}}<a class="stp-status badge badge-info" href="javascript:void(0)" data-id="{{: employee_id}}">Enabled</a>{{else}}<a class="stp-status badge badge-danger" href="javascript:void(0)" data-id="{{: employee_id}}">Disabled</a>{{/if}}</td><td><button class="stp-edit btn btn-info btn-sm" data-id="{{: employee_id}}">Edit</button></td><td><button class="btn btn-danger btn-sm stp-delete"  data-id="{{: employee_id}}">Delete</button></td></tr>
        </script>
       <div class="row">
            <div class="col-12 col-md-8">
            <div id="stp-error"></div>
            <table class="table table-striped" id="employee-table">
            <thead class="thead-dark text-center">
                <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Code</th>
                <th scope="col">Status</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody id="employee_list" class="font-weight-bold text-center valign-center">

            </tbody>
            </table>
            </div>
            <div class="col-12 col-md-4">
                <div id="create_employee" class="p-3 bg-light shadow-lg mt-3">
                    <form id="create_employee_form">
                    <strong class="text-dark h6">Create Employee</strong>
                    <input id="add_name" type="text" placeholder="Employee Name" required name="employee" class="form-control mb-2 mt-2"/>
                    <input id="add_code" type="text" placeholder="Employee Code" required name="code" class="form-control mb-2"/>
                    <input type="submit" class="btn btn-primary btn-block" value="Submit" />
                    </form>
                </div>
            </div>
       </div>
       <?php
    }

    function create_employee(){
        if(current_user_can('administrator')){
           $name = (isset($_POST['name']) && $_POST['name'] != "") ? sanitize_text_field($_POST['name']): false;
           $code = (isset($_POST['code']) && $_POST['code'] != "") ? sanitize_text_field($_POST['code']): false;
            if($name != false && $code != false){
                $data = array('name'=>$name, 'code'=>$code, 'status'=>true);
                global $wpdb;
                $wpdb->show_errors = false;
                $table_name = $wpdb->prefix . 'employee';
                
                $result = $wpdb->insert( $table_name, $data);
                if($wpdb->last_error !== ''){
                    echo json_encode(array('status'=>false, 'msg'=>$wpdb->last_error));
                }else{
                    if($result){
                        echo json_encode(array('status'=>true,'msg'=>'Employee was added successfully'));
                    }else{
                        echo json_encode(array('status'=>false,'msg'=>'There was some problem in adding employee'));
                    }
                }
            }
        }
        wp_die();
    }

    function get_all_employee(){
        if(current_user_can('administrator')){
                 global $wpdb;
                 $table_name = $wpdb->prefix . 'employee';
                 $query = 'SELECT * FROM '.$table_name.' ORDER BY employee_id DESC';
                 $result = $wpdb->get_results( $query, 'ARRAY_A' ); 
                 echo json_encode($result);
         }
         wp_die();
    }

    static function check_code($code){
                 $code = sanitize_text_field($code);
                 global $wpdb;
                 $table_name = $wpdb->prefix . 'employee';
                 $query = 'SELECT * FROM '.$table_name.' Where code="'.$code.'" and status=true';
                 $result = $wpdb->get_row( $query, 'ARRAY_A' ); 
                 if($result != null){
                    return ($result);
                 }else{
                    return false;
                }
    }

    function change_state(){
        if(current_user_can('administrator')){
            if(isset($_POST['id']) && $_POST['id'] != ""){
                $id = intval(sanitize_text_field($_POST['id']));
                global $wpdb;
                $table_name = $wpdb->prefix . 'employee';
                $query = 'UPDATE '.$table_name.' SET status = !status WHERE employee_id='.$id;
                $result = $wpdb->query($query);
                if($result !== false){
                    echo json_encode(array('status'=>true,'msg'=>"State changed"));
                }else{
                    echo json_encode(array('status'=>false,'msg'=>"There was some problem in changing state"));
                }
            }else{
                echo json_encode(array('status'=>false,'msg'=>"There was some problem in changing state"));
            }
        }
    wp_die();
    }

    function delete_employee(){
        if(current_user_can('administrator')){
            if(isset($_POST['id']) && $_POST['id'] != ""){
                $id = intval(sanitize_text_field($_POST['id']));
                global $wpdb;
                $table_name = $wpdb->prefix . 'employee';
                $result = $wpdb->delete($table_name, array('employee_id'=>$id));
                if($result !== false){
                    echo json_encode(array('status'=>true,'msg'=>"Employee Deleted"));
                }else{
                    echo json_encode(array('status'=>false,'msg'=>"There was some problem in deleting employee"));
                }
            }else{
                echo json_encode(array('status'=>false,'msg'=>"There was some problem in deleting employee"));
            }
        }
    wp_die();
    }

    function edit_employee(){
        if(current_user_can('administrator')){
            if(isset($_GET['employee_id']) && $_GET['employee_id'] != "" && isset($_GET['name']) && $_GET['name'] != "" && isset($_GET['code']) && $_GET['code'] != ""){
                $id = intval(sanitize_text_field($_GET['employee_id']));
                $name = (sanitize_text_field($_GET['name']));
                $code = (sanitize_text_field($_GET['code']));
                global $wpdb;
                $wpdb->show_errors = false;
                $table_name = $wpdb->prefix . 'employee';
                $query = 'UPDATE '.$table_name.' SET name = "'.$name.'", code = "'.$code.'" WHERE employee_id='.$id;
                $result = $wpdb->query($query);
                if($wpdb->last_error !== ''){
                    echo json_encode(array('status'=>false, 'msg'=>$wpdb->last_error));
                }else{
                    if($result){
                        echo json_encode(array('status'=>true,'msg'=>'Employee updated successfully'));
                    }else{
                        echo json_encode(array('status'=>false,'msg'=>'There was some problem in updating'));
                    }
                }
            }else{
                echo json_encode(array('status'=>false,'msg'=>"There was some problem in updating"));
            }
        }
    wp_die();
    }

}

new Class_Stp_Strc_Staff($this->plugin_name);