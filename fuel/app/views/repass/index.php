<?php
	$model_utility = new Model_Utility();
	$result_message = \Session::get_flash('result_message');
	$error_message = \Session::get_flash('error_message');
	if($result_message != null){
		$result_string = 'success';
		$alert_message = $result_message;
	} else {
		$result_string = 'error';
		$alert_message = $error_message;
	}
?>
<div id="login">
	<div class="form-signin">
	    <h2 class="form-signin-heading">Reissue password</h2>
	    <?php echo \Form::open(array('name'=>'repass','method'=>'post')); ?>
	    <?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
		<?php if(!empty($result_message) || !empty($error_message)):?>
		<div class="row-fluid">
		    <div class="alert alert-<?php echo $model_utility->h($result_string);?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
		        <h4><?php echo $model_utility->h(ucfirst($result_string));?></h4><?php echo $model_utility->h($alert_message);?>
		    </div>
		</div>
		<?php endif;?>
	    <?php echo \Form::input('username',\Input::post('username'),array('placeholder'=>'UserName'));?>
	    <?php echo \Form::input('email',\Input::post('email'),array('placeholder'=>'MailAddress'));?>
	    <?php echo \Form::submit('submit','Reissue procedure',array('class' => 'btn btn-primary btn-large'));?>
	    <?php echo \Form::close();?>
	</div> <!-- /.form-signin -->
</div>