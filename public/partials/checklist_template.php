<?php
$post_id = $post->ID;
$error = "";
if(isset($_POST['code']) && isset($_POST['code']) != ""){
    $employee = Class_Stp_Strc_Staff::check_code(sanitize_text_field($_POST['code']));
    if($employee){
        include 'checklist_form.php';
    }else{
        $error = "Please re-check employee code";
    }
}

if((!isset($_POST['code']) && $error == "") || (isset($_POST['code']) && $error != "")){
    ?>
    <div class="stp-container">
    <h4>Enter your employee code to see the checklist</h4>
    <?php if($error != ""): ?>
    <div class="stp-error"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="stp-row">
            <input type="text" required name="code" placeholder="Enter your code" value="<?php if(isset($_POST['code'])) echo esc_attr($_POST['code']); ?>"/> <button type="submit" class="stp-btn">Login</button>
        </div>
    </form>
    </div>
    <?php
}