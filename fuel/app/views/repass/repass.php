<div id="login">
	<div class="form-signin">
			<?php echo \Form::open(array('name'=>'repass','method'=>'post')); ?>
			<h2 class="form-signin-heading">PassWord Reset</h2>
			<?php
				$username = !empty($username)? $username : '';
				if(!empty($errmsg)){
				echo '<div class="alert alert-error">';
				foreach($errmsg as $key => $val){
					echo $utility->h($val).'<br />';
				}
				echo '</div>';
			}
			?>
			<?php echo \Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
			<?php echo \Form::input('repass[password]','', array('type'=>'password','placeholder'=>'PassWord','id'=>'inputPassword','class' => 'input-block-level','required'=>'required'));?>
			<?php echo \Form::input('repass[password_conf]','', array('type'=>'password','placeholder'=>'PassWord Confirmation','id'=>'inputPasswordConf','class' => 'input-block-level','required'=>'required'));?>
			<?php echo \Form::submit('submit','Send',array('type'=>'submit','class' => 'btn btn-large btn-block btn-inverse'));?>
			<?php echo \Form::close();?>
		<?php echo \Html::anchor('repass','Lost your password?');?>
	</div>
</div>