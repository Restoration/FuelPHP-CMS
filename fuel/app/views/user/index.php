<?php include APPPATH . 'views/parts/header.php';?>
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
					UserName：<?php echo $utility->h($username);?>
					<div class="clear"></div>
					<?php echo \Form::hidden('user[username]',$utility->h($username));?>
					<?php echo \Form::input('user[old_password]','', array('class' => 'form-control span8','placeholder'=>'Old PassWord','type'=>'password'));?>
					<?php echo \Form::input('user[password]','', array('class' => 'form-control span8','placeholder'=>'New PassWord','type'=>'password'));?>
					<?php echo \Form::input('user[password_conf]','', array('class' => 'form-control span8','placeholder'=>'New PassWord Confirmation','type'=>'password'));?>
					<?php echo \Form::input('user[email]',$utility->h($email), array('class' => 'form-control span8','placeholder'=>'Email'));?>
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
<?php include APPPATH . 'views/parts/footer.php';?>