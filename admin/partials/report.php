<div class="row my-3">
<?php
foreach($checklists as $list){
    ?>
    <div class="col-12 col-md-6 my-3">
    <div class="shadow">
        <h3 class="m-0 p-2 bg-primary text-light"><?php echo get_the_title($list['checklist_id']); ?> : <span class="text-italic font-weight-light"><?php echo ($list['name']); ?></span><span class="float-right small"><?php echo date(get_option( 'date_format' ), strtotime($list['date'])); ?></span></h3>
        <div class="p-3">
        <?php
        $original_list = json_decode($list['original_list']);
        $selected_items = $this->creating_array(json_decode($list['selected_items']));
        foreach($original_list as $original){
            ?>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="<?php echo md5($original->item); ?>" <?php echo isset($selected_items[md5($original->item)]) ? 'checked="checked"' : ""; ?>>
                <label class="custom-control-label" for="<?php echo md5($original->item); ?>"><?php echo ($original->item); ?></label>
            </div>
            <?php
        }
        ?>
        </div>
    </div>
    </div>
    <?php
}
?>
</div>