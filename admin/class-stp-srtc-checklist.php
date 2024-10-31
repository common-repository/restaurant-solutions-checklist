<?php

class Stp_Srtc_Checklist{
    function __construct(){
        add_action( 'init', array($this, 'create_checklist_type') );
        add_action( 'add_meta_boxes', array($this,'checklist') );
        add_action( 'admin_enqueue_scripts', array($this,'enqueue_scripts'),10,1 );
        add_action( 'save_post', array($this,'save_meta_box_data') );
        add_action( 'load-edit.php', function() {
            add_filter( 'views_edit-stp_checklist', array($this,'talk_tabs') ); 
             
          });

        add_filter( 'edit_form_top', array($this,'talk_tabs_edit') );
    }

    function enqueue_scripts( $hook ) {  
  
        global $post;  
      
        if ( $hook == 'post-new.php' || $hook == 'post.php' ) {  
            if ( 'stp_checklist' === $post->post_type ) {       
                wp_enqueue_script(  'stp-jsrender', plugin_dir_url( __FILE__ ) .'js/jsrender.min.js',array('jquery') );  
                wp_enqueue_script(  'stp-checklist', plugin_dir_url( __FILE__ ) .'js/checklist.js',array('stp-jsrender') );  
            }  
        }  
    }  

    function create_checklist_type() {
        register_post_type( 'stp_checklist',
          array(
            'labels' => array(
              'name' => __( 'Checklists' ),
              'singular_name' => __( 'Checklists' ),
              'add_new_item' => __('Add new checklist')
            ),
            'public' => true,
            'has_archive' => true,
            'publicaly_queryable' => true,
            'rewrite' => false,
            'query_var' => true,
            'exclude_from_search' => false,
            'menu_icon'   => 'dashicons-clipboard',
            'show_ui' => true,
            'supports'=>array('title')
          )
        );
    }

    function checklist(){
        add_meta_box(
            'stp-checklist',
            __( 'Checklist points', 'stp-srtc' ),
            array($this,'metabox_form'),
            'stp_checklist',
            'advanced'
        );
        /*
        add_meta_box(
            'stp-checklist-link',
            __( 'Links', 'stp-srtc' ),
            array($this,'metabox_links'),
            'stp_checklist',
            'side',
            'high'
        );
        */
    }

    function metabox_links(){
        ?>
        <a style="padding:10px; text-align:center; color: #fff;
    background-color: #ee6443; display:block; font-weight:bold; font-size:1rem; text-decoration:none; margin-bottom:10px;" href="<?php echo admin_url('edit.php?post_type=stp_checklist&page=stp_checklist_option'); ?>">Back to Dashboard</a>
    <a style="padding:10px; text-align:center; color: #fff;
    background-color: #ee6443; display:block; font-weight:bold; font-size:1rem; text-decoration:none; margin-bottom:10px;" href="<?php echo admin_url('edit.php?post_type=stp_checklist'); ?>">All Checklists</a>
    <a style="padding:10px; text-align:center; color: #fff;
    background-color: #ee6443; display:block; font-weight:bold; font-size:1rem; text-decoration:none; margin-bottom:10px;" href="<?php echo admin_url('edit.php?post_type=stp_checklist&page=stp_checklist_option'); ?>">Staff Management</a>
    <a style="padding:10px; text-align:center; color: #fff;
    background-color: #ee6443; display:block; font-weight:bold; font-size:1rem; text-decoration:none; margin-bottom:10px;" href="<?php echo admin_url('admin.php?page=stp_checklist_option&tab=report'); ?>">Reports</a>
        <?php
    }

    function metabox_form($post, $callback_args ){
        wp_nonce_field( 'global_notice_nonce', 'global_notice_nonce' );
        $items = get_post_meta( $post->ID, 'item',true);
        ?>
        <script type="text/javascript">
        var checklist = <?php echo json_encode(($items == false) ? array(): array_values($items)); ?>;
        </script>
        <script id="checklist-template" type="text/x-jsrender">
        <div class="checklist-item-container" style="margin-bottom:10px; display:flex;">
        <input required type="text" name="item[{{: count}}][item]" value="{{: value.item}}" style="flex:1"/><a class="stp-remove button">Remove</a>
        </div>
        </script>
        <div id="checklist-form"></div>
        <br>
        <a id="add_item" class="button">Add Checklist Item</a>
        <?php
    }

    function save_meta_box_data( $post_id ) {
        // Check if our nonce is set.
        if ( ! isset( $_POST['global_notice_nonce'] ) ) {
            return;
        }

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $_POST['global_notice_nonce'], 'global_notice_nonce' ) ) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if ( isset( $_POST['post_type'] ) && 'stp_checklist' == $_POST['post_type'] ) {

            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return;
            }

        }
        else {

            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return;
            }
        }

        // Make sure that it is set.
        if ( ! isset( $_POST['item'] ) ) {
            delete_post_meta( $post_id, 'item');
            return;
        }

        // Sanitize user input.
        $item = ( $_POST['item'] );
        

        // Update the meta field in the database.
        update_post_meta( $post_id, 'item', $item );
       
    }

    function talk_tabs() {
        
        echo '<div class="pi-header">
            <div class="pi-tabs-cont">
            <a class="nav-tab pi-tab-button" href="'.admin_url('edit.php?post_type=stp_checklist&page=stp_checklist_option').'">Back to Home</a>
           <a class="nav-tab pi-tab-button" href="'.admin_url('post-new.php?post_type=stp_checklist').'">Add New List</a>
           <a class="nav-tab pi-tab-button active" href="'.admin_url('edit.php?post_type=stp_checklist').'">All Checklists</a>
           <a class="nav-tab pi-tab-button" href="'.admin_url('admin.php?page=stp_checklist_option&tab=report').'">Reports</a>
            </div>
        </div>';
        
       }

       function talk_tabs_edit($post) {

        if($post->post_type == 'stp_checklist'){
        echo '<div class="pi-header">
            <div class="pi-tabs-cont">
            <a class="nav-tab pi-tab-button" href="'.admin_url('edit.php?post_type=stp_checklist&page=stp_checklist_option').'">Back to Home</a>
           <a class="nav-tab pi-tab-button active" href="'.admin_url('post-new.php?post_type=stp_checklist').'">Add New List</a>
           <a class="nav-tab pi-tab-button " href="'.admin_url('edit.php?post_type=stp_checklist').'">All Checklists</a>
           <a class="nav-tab pi-tab-button" href="'.admin_url('admin.php?page=stp_checklist_option&tab=report').'">Reports</a>
            </div>
        </div>';
        }
       }
    
}

new Stp_Srtc_Checklist();


  
  # echo the tabs
  