<?php

class Stp_Srtc_Menu{

    public $plugin_name;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        
    }

    function plugin_menu(){
        
        $this->menu = add_submenu_page(
            'edit.php?post_type=stp_checklist',
            __( 'Setting', 'pi-edd' ),
            'Home',
            'manage_options',
            'stp_checklist_option',
            array($this, 'menu_option_page'),
            'dashicons-randomize',
            0
        );
        
        add_action("load-".$this->menu, array($this,"bootstrap_style"));
        add_action($this->plugin_name.'_tab',array($this,"custom_link"),2);

    }

    public function bootstrap_style() {
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/stp-srtc-admin.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );
        wp_enqueue_script( 'stp-jsrender', plugin_dir_url( __FILE__ ) .'js/jsrender.min.js',array('jquery') );  
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.2/themes/smoothness/jquery-ui.css');

    }

    function menu_option_page(){
        ?>
        <div class="container-fluid pl-0" >
            <div class="row">
                    <div class="col-12">
        <h1 class="wp-heading-inline" style="line-height:29px ;font-size: 23px;
    font-weight: 400;margin-top:20px; margin-bottom:20px;">
        <?php if($_GET['tab']=='report'){ ?>
        Report
        <?php }else{ ?>
        Home page
        <?php } ?>
        </h1>
            </div>
            </div>
            </div>
        <div class="container-fluid pl-0" >
            <div class="row">
                    <div class="col-12">
                        <div class='bg-dark'>
                        <div class="row">
                            
                            <div class="col-12 col-sm-12 d-flex">
                                <?php do_action($this->plugin_name.'_tab'); ?>
                            </div>
                        </div>
                        </div>
                    </div>
            </div>
            <div class="row">
                <div class="col-12">
                <div class="bg-light border pl-3 pr-3 pb-3 pt-3">
                    <div class="row">
                        <div class="col">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div>
                        <?php do_action($this->plugin_name.'_promotion'); ?>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <?php
    }

    function custom_link(){
        echo '<a href="'.admin_url('post-new.php?post_type=stp_checklist').'" class=" px-3 py-3 text-light d-flex align-items-center  border-left border-right  bg-secondary">Add New List</a>';
        echo '<a href="'.admin_url('edit.php?post_type=stp_checklist').'" class=" px-3  py-3 text-light d-flex align-items-center  border-left border-right  bg-secondary">All Checklists</a>';
    }

}