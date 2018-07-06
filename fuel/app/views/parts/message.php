<?php
	$utility = new Utility();
	$result_message = \Session::get_flash('result_message');
	$error_message = \Session::get_flash('error_message');
	$errors = \Session::get_flash('errors');
	if($result_message != null){
		$result_string = 'success';
		$alert_message = $result_message;
	} else {
		$result_string = 'error';
		$alert_message = $error_message;
	}
if(!empty($result_message) || !empty($error_message)):?>
<div class="row-fluid">
    <div class="alert alert-<?php echo $utility->h($result_string);?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><?php echo $utility->h(ucfirst($result_string));?></h4><?php echo $utility->h($alert_message);?>
	    <?php
		if(!empty($errors)){
			echo '<p>';
	        foreach ($errors as $error){
	            echo $utility->h($error).'<br />';
	        }
	        echo '</p>';
	    }
	    ?>
    </div>
</div>
<?php endif;?>