<?php

class Class_Stp_Strc_Report{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'report';

    private $tab_name = "Report";

    private $setting_key = 'report_settting';

   

    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;
        
        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),5);
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
       <form method="POST">
        <div class="row justify-content-center">
            <div class="col-12 col-md-3">
                <input type="text" id="date" class="form-control" name="date" placeholder="Click to select date" value="<?php echo isset($_POST['date']) ? esc_attr($_POST['date']) : ""; ?>">
            </div>
            <div class="col-12 col-md-3">
                <button type="submit" class="btn btn-secondary">Generate Report</button>
            </div>
        </div>
       </form>
       <?php
       if(isset($_POST['date']) && $_POST['date'] != ""){
            $checklists = $this->get_report($_POST['date']);
            include 'partials/report.php';
       }
    }

   function get_report($date){
    global $wpdb;
    $wpdb->show_errors = false;
    $table_name = $wpdb->prefix . 'checklist'; 
    $query = 'SELECT * FROM '.$table_name.' Where date = "'.sanitize_text_field($date).'"';
    $result = $wpdb->get_results($query,  ARRAY_A);
    return ($result);
   }

   function creating_array($items){
       $result = array();
       if(is_array($items) && count($items) > 0){
       foreach($items as $item){
            $result[md5($item->item)] = $item->item;
       }
    }
       return $result;
   }

}

new Class_Stp_Strc_Report($this->plugin_name);