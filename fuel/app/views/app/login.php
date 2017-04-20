<?php $model_utility = new Model_Utility();?>
<div id="login">
	<div class="form-signin">
			<?php echo Form::open('app/login');?>
			<h2 class="form-signin-heading">Login</h2>
			<?php
				$username = !empty($username)? $username : '';
				if(!empty($errmsg)){
				echo '<div class="alert alert-error">';
				foreach($errmsg as $key => $val){
					echo $model_utility->h($val).'<br />';
				}
				echo '</div>';
			}
			?>
			<?php echo Form::input('username',Input::post('username'),array('type'=>'text','placeholder'=>'User Name','class'=>'input-block-level'));?>
			<?php echo Form::input('password',Input::post('password'),array('type'=>'password','placeholder'=>'PassWord','class'=>'input-block-level'));?>
			<?php echo Form::hidden(\Config::get('security.csrf_token_key'), \Security::fetch_token());?>
			<button class="btn btn-large btn-block btn-inverse" type="submit">Sign in</button>
			<?php echo Form::close();?>
		<?php echo Html::anchor('repass','Lost your password?');?>
	</div>
</div>