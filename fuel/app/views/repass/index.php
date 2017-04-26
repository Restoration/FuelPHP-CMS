
<?php
	$model_utility = new Model_Utility();
?>
<div id="login">
	<div class="form-signin">
	    <h2 class="form-signin-heading">Reset password</h2>
			<?php echo \Form::open(array('name'=>'repass','method'=>'post','action'=>'repass/complete')); ?>
			<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
			<?php include APPPATH . 'views/parts/message.php';?>
		    <?php echo \Form::input('repass[username]',\Input::post('username'),array('type'=>'text','id'=>'inputName','placeholder'=>'UserName','class'=>'form-control form-control-lg'));?>
		    <?php echo \Form::input('repass[email]',\Input::post('email'),array('type'=>'email','id'=>'inputEmail','placeholder'=>'MailAddress','class'=>'form-control form-control-lg'));?>
		    <?php echo \Form::submit('submit','Send',array('class' => 'btn btn-primary btn-large'));?>
	    <?php echo \Form::close();?>
	</div> <!-- /.form-signin -->
</div>
