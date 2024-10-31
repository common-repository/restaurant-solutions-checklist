<?php
$check_today = date("h:i a");
if(strtotime($check_today) > strtotime('04:00 am')){
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d',strtotime("-1 days"));
    $tomorrow = date('Y-m-d',strtotime("+1 days"));
}else{
    $today = date('Y-m-d',strtotime("-1 days"));
    $yesterday = date('Y-m-d',strtotime("-2 days"));
    $tomorrow = date('Y-m-d');
}
    $items = (get_post_meta($post->ID, 'item',true));
?>
<div class="stp-container">
<div id="stp-error"></div>
    <div class="stp-checklist-cont">
        <div class="stp-checklist-content">
            <?php 
            if(is_array($items) && count($items) > 0){
                $count = 0;
                foreach($items as $item){
                    ?>
                <div class="stp-item-container">
                    <input class="stp-checklist-item" type="checkbox" name="<?php echo md5($item['item']); ?>" value="<?php echo $item['item']; ?>" id="<?php echo md5($item['item']); ?>"><label for="<?php echo md5($item['item']); ?>"><?php echo $item['item']; ?></label>
                </div>
                    <?php
                    $count++;
                }
            }else{
                echo "There are no items in this checklist";
            }
            ?>
            <input type="hidden" id="code" name="code" value="<?php echo esc_attr($_POST['code']); ?>" />
            <input type="hidden" id="checklist_id" name="checklist_id" value="<?php echo esc_attr($post->ID); ?>" />
        </div>
        <div class="stp-dates">
            <div id="stp-yesterday" class="stp-date"><input type="radio" name="checklist_date" id="yesterday" value="<?php echo esc_attr($yesterday); ?>"><label for="yesterday">Yesterday<br><small><?php //echo date(get_option( 'date_format' ),strtotime($yesterday)); ?></small></label></div>
            <div id="stp-today" class="stp-date"><input type="radio" name="checklist_date" id="today" value="<?php echo esc_attr($today); ?>" checked="checked"><label for="today">Today<br><small><?php //echo date(get_option( 'date_format' ),strtotime($today)); ?></small></label></div>
            <div id="stp-tomorrow" class="stp-date"><input type="radio" name="checklist_date" id="tomorrow" value="<?php echo esc_attr($tomorrow); ?>"><label for="tomorrow">Tomorrow<br><small><?php //echo date(get_option( 'date_format' ),strtotime($tomorrow)); ?></small></label></div>
        </div>
    </div>
</div>