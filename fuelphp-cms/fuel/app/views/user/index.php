<?php
	include APPPATH . 'views/parts/header.php';
	$model_main = new Model_Main();
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

<?php if(!empty($result_message) || !empty($error_message)):?>
<div class="row-fluid">
    <div class="alert alert-<?php echo $model_utility->h($result_string);?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
        <h4><?php echo $model_utility->h(ucfirst($result_string));?></h4><?php echo $model_utility->h($alert_message);?>
    </div>
</div>
<?php endif;?>

<div class="row-fluid">
	<!-- block -->
	<div class="block">
		<div class="navbar navbar-inner block-header">
			<div class="muted pull-left"><i class="icon-user"></i>Users</div>
		</div>
		<div class="block-content collapse in">
	        <div class="span12">
					<?php echo \Form::open('user/update');?>
					<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
					ユーザー名：<?php echo $model_utility->h($username);?>
					<div class="clear"></div>
					<?php echo \Form::input('username',$model_utility->h($username), array('type'=>'hidden'));?>
					<?php echo \Form::input('old_password','', array('class' => 'form-control span8','placeholder'=>'Old PassWord'));?>
					<?php echo \Form::input('password','', array('class' => 'form-control span8','placeholder'=>'New PassWord'));?>
					<?php echo \Form::input('email',$model_utility->h($email), array('class' => 'form-control span8','placeholder'=>'Confirm New PassWord'));?>
				</div>
				<div class="right_link">
					<?php echo \Form::submit('save','Update', array('class' => 'form-control btn btn-primary edit_btn'));?>
				</div>
				<?php echo \Form::close();?>
	        </div>
		</div>
	</div>
	<!-- /block -->
</div>
<?php echo \Pagination::instance('pagination');?>
<?php include APPPATH . 'views/parts/footer.php';?>








